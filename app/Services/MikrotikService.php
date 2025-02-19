<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\Device;

class MikrotikService
{
    protected $client;
    protected $isConnected = false;
    protected $retryAttempts = 3;

    public function __construct()
    {
        $maxRetries = 3;
        $retryDelay = 2;

        for ($i = 1; $i <= $maxRetries; $i++) {
            try {
                // Ensure config values are not null
                $host = config('mikrotik.host') ?? '192.168.88.1';
                $user = config('mikrotik.user') ?? 'admin';
                $pass = config('mikrotik.pass');
                $port = config('mikrotik.port') ?? 8728;

                if (empty($user) || empty($pass)) {
                    throw new \Exception('Mikrotik credentials not configured');
                }

                $this->client = new Client([
                    'host' => $host,
                    'user' => $user,
                    'pass' => $pass,
                    'port' => (int)$port,
                ]);

                // Test connection
                $query = new Query('/system/resource/print');
                $this->client->query($query)->read();
                
                $this->isConnected = true;
                Log::info('Successfully connected to Mikrotik');
                break;
            } catch (\Exception $e) {
                Log::warning("Mikrotik connection attempt {$i} failed: " . $e->getMessage());
                if ($i < $maxRetries) {
                    sleep($retryDelay);
                }
            }
        }
    }

    protected function getMikrotikHost()
    {
        // Try to get cached IP
        $cachedIp = Cache::get('mikrotik_ip');
        if ($cachedIp) {
            return $cachedIp;
        }

        // Discover Mikrotik by MAC address
        $wirelessMac = config('mikrotik.wireless_mac');
        if ($wirelessMac) {
            $ip = $this->findMikrotikByMac($wirelessMac);
            if ($ip) {
                Cache::put('mikrotik_ip', $ip, now()->addMinutes(5));
                return $ip;
            }
        }

        // Fallback to configured host
        return config('mikrotik.host');
    }

    protected function findMikrotikByMac($mac)
    {
        // Use ARP to find device
        exec("arp -a | grep '$mac' | awk '{print $2}'", $output);
        if (!empty($output[0])) {
            return trim($output[0], '()');
        }
        return null;
    }

    public function getActiveUsers()
    {
        if (!$this->isConnected) {
            return ['data' => []];
        }

        try {
            $query = new Query('/ip/hotspot/active/print');
            $activeUsers = $this->client->query($query)->read();
            
            // Save and update devices in database
            $this->saveDevices($activeUsers);
            
            return ['data' => $activeUsers];
        } catch (\Exception $e) {
            Log::error('Error getting active users: ' . $e->getMessage());
            return ['data' => []];
        }
    }

    protected function saveDevices($activeUsers)
    {
        try {
            // Mark all devices as disconnected first
            Device::query()->update(['status' => 'Disconnected']);

            foreach ($activeUsers as $user) {
                if (isset($user['mac-address'])) {
                    $bytesIn = $user['bytes-in'] ?? 0;
                    $bytesOut = $user['bytes-out'] ?? 0;
                    $totalBytes = $this->formatBytes($bytesIn + $bytesOut);

                    Device::updateOrCreate(
                        ['mac_address' => $user['mac-address']],
                        [
                            'name' => $user['user'] ?? 'Unknown Device',
                            'status' => 'Active',
                            'last_seen' => now(),
                            'bandwidth_used' => $totalBytes
                        ]
                    );
                }
            }
        } catch (\Exception $e) {
            Log::error('Error saving devices: ' . $e->getMessage());
        }
    }

    public function createVoucher($username, $password, $profile, $validity)
    {
        if (!$this->isConnected) {
            return [
                'status' => 'error',
                'message' => 'Not connected to Mikrotik'
            ];
        }

        try {
            $query = new Query('/ip/hotspot/user/add');
            $query->equal('name', $username);
            $query->equal('password', $password);
            $query->equal('profile', $profile);
            $query->equal('limit-uptime', $validity);
            
            return [
                'status' => 'success',
                'data' => $this->client->query($query)->read()
            ];
        } catch (Exception $e) {
            Log::error('Mikrotik createVoucher error: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => $e->getMessage()
            ];
        }
    }

    public function getBandwidthUsage()
    {
        if (!$this->isConnected) {
            return [
                'total' => '0 B',
                'today' => '0 B'
            ];
        }

        try {
            $interface = config('mikrotik.interface', 'ether1');
            
            // Get interface statistics
            $query = new Query('/interface/print');
            $query->where('name', $interface);
            $stats = $this->client->query($query)->read();

            if (empty($stats)) {
                return [
                    'total' => '0 B',
                    'today' => '0 B'
                ];
            }

            // Get total bytes
            $totalRxBytes = $stats[0]['rx-byte'] ?? 0;
            $totalTxBytes = $stats[0]['tx-byte'] ?? 0;
            $totalBytes = $totalRxBytes + $totalTxBytes;

            // Get today's bytes (you might want to store previous day's values in database)
            $todayBytes = $totalBytes * 0.2; // This is a placeholder calculation

            return [
                'total' => $this->formatBytes($totalBytes),
                'today' => $this->formatBytes($todayBytes)
            ];
        } catch (\Exception $e) {
            Log::error('Error getting bandwidth usage: ' . $e->getMessage());
            return [
                'total' => '0 B',
                'today' => '0 B'
            ];
        }
    }

    public function getRouterBandwidth()
    {
        if (!$this->isConnected) {
            Log::warning('Not connected to Mikrotik');
            return $this->getEmptyBandwidthStats();
        }

        try {
            // Get all active hotspot users
            $query = new Query('/ip/hotspot/active/print');
            $activeUsers = $this->client->query($query)->read();
            
            $totalRxBits = 0;
            $totalTxBits = 0;

            foreach ($activeUsers as $user) {
                // Convert bytes to bits per second
                $rxBits = isset($user['bytes-in']) ? ($user['bytes-in'] / 8) : 0;
                $txBits = isset($user['bytes-out']) ? ($user['bytes-out'] / 8) : 0;
                
                $totalRxBits += $rxBits;
                $totalTxBits += $txBits;
            }

            // Get interface traffic for wlan1
            $interface = config('mikrotik.interface');
            $query = new Query('/interface/monitor-traffic');
            $query->equal('interface', $interface);
            $query->equal('once', '');
            
            $response = $this->client->query($query)->read();
            
            if (isset($response[0])) {
                $rx_bits = $response[0]['rx-bits-per-second'] ?? 0;
                $tx_bits = $response[0]['tx-bits-per-second'] ?? 0;
                
                // Combine interface and user statistics
                $totalRxBits += $rx_bits;
                $totalTxBits += $tx_bits;
                
                Log::info("Total bandwidth - RX: {$totalRxBits} bits/s, TX: {$totalTxBits} bits/s");
                
                return [
                    'rx_rate' => $this->formatBytes($totalRxBits / 8) . '/s',
                    'tx_rate' => $this->formatBytes($totalTxBits / 8) . '/s',
                    'total_rate' => $this->formatBytes(($totalRxBits + $totalTxBits) / 8) . '/s'
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error getting router bandwidth: ' . $e->getMessage());
        }

        return $this->getEmptyBandwidthStats();
    }

    private function getEmptyBandwidthStats()
    {
        return [
            'rx_rate' => '0 B/s',
            'tx_rate' => '0 B/s',
            'total_rate' => '0 B/s'
        ];
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}