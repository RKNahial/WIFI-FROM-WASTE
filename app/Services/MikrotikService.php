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
        $this->connect();
    }

    protected function connect()
    {
        for ($attempt = 1; $attempt <= $this->retryAttempts; $attempt++) {
            try {
                $config = new Config([
                    'host' => $this->getMikrotikHost(),
                    'user' => config('mikrotik.username'),
                    'pass' => config('mikrotik.password'),
                    'port' => (int)config('mikrotik.port'),
                    'ssl' => config('mikrotik.api_ssl')
                ]);

                $this->client = new Client($config);
                $this->isConnected = true;
                break;
            } catch (\Exception $e) {
                Log::warning("Mikrotik connection attempt {$attempt} failed: " . $e->getMessage());
                if ($attempt === $this->retryAttempts) {
                    $this->isConnected = false;
                    Log::error('All Mikrotik connection attempts failed');
                }
                sleep(1); // Wait 1 second before retry
            }
        }
    }

    protected function getMikrotikHost()
    {
        // Try to get stored IP from database first
        $storedConnection = $this->getStoredConnection();
        if ($storedConnection && $this->pingHost($storedConnection)) {
            return $storedConnection;
        }

        // If stored connection fails, try to discover
        $discoveredIp = $this->discoverMikrotik();
        if ($discoveredIp) {
            $this->storeConnection($discoveredIp);
            return $discoveredIp;
        }

        // Fallback to configured host
        return config('mikrotik.host');
    }

    protected function pingHost($ip)
    {
        exec(sprintf('ping -c 1 -W 1 %s', escapeshellarg($ip)), $output, $returnVar);
        return $returnVar === 0;
    }

    protected function discoverMikrotik()
    {
        $wirelessMac = config('mikrotik.wireless_mac');
        if (!$wirelessMac) {
            return null;
        }

        // Try to find device by MAC address
        exec("arp -a | grep -i '$wirelessMac' | awk '{print $2}'", $output);
        if (!empty($output[0])) {
            return trim($output[0], '()');
        }

        return null;
    }

    protected function getStoredConnection()
    {
        // Get from cache first (faster)
        if ($cachedIp = Cache::get('mikrotik_active_ip')) {
            return $cachedIp;
        }

        // Get from database
        $connection = \DB::table('mikrotik_connections')
            ->where('is_active', true)
            ->first();

        return $connection ? $connection->ip_address : null;
    }

    protected function storeConnection($ip)
    {
        // Store in cache
        Cache::put('mikrotik_active_ip', $ip, now()->addDay());

        // Store in database
        \DB::table('mikrotik_connections')
            ->updateOrInsert(
                ['ip_address' => $ip],
                [
                    'is_active' => true,
                    'last_connected' => now(),
                ]
            );
    }

    public function getActiveUsers()
    {
        if (!$this->isConnected) {
            return ['data' => []];
        }

        try {
            $query = new Query('/ip/hotspot/active/print');
            $activeUsers = $this->client->query($query)->read();
            
            // Save devices to database
            foreach ($activeUsers as $user) {
                if (isset($user['mac-address'])) {
                    Device::updateOrCreate(
                        ['mac_address' => $user['mac-address']],
                        [
                            'name' => $user['user'] ?? 'Unknown Device',
                            'status' => 'Active',
                            'last_seen' => now(),
                            'bandwidth_used' => $this->formatBytes($user['bytes-in'] ?? 0)
                        ]
                    );
                }
            }

            // Mark disconnected devices
            Device::where('updated_at', '<', now()->subMinutes(5))
                ->where('status', 'Active')
                ->update(['status' => 'Disconnected']);

            return ['data' => $activeUsers];
        } catch (\Exception $e) {
            Log::error('Error getting active users: ' . $e->getMessage());
            return ['data' => []];
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
            $query = new Query('/interface/monitor-traffic');
            $query->equal('interface', $interface);
            $query->equal('once');
            
            $traffic = $this->client->query($query)->read();
            
            if (empty($traffic)) {
                return [
                    'total' => '0 B',
                    'today' => '0 B'
                ];
            }

            $totalRx = array_sum(array_column($traffic, 'rx-bits-per-second'));
            $totalTx = array_sum(array_column($traffic, 'tx-bits-per-second'));
            
            $totalBytes = ($totalRx + $totalTx) / 8;
            
            return [
                'total' => $this->formatBytes($totalBytes),
                'today' => $this->formatBytes($totalBytes / 2)
            ];
        } catch (\Exception $e) {
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
            // Get all interfaces first
            $query = new Query('/interface/print');
            $interfaces = $this->client->query($query)->read();
            
            if (empty($interfaces)) {
                Log::error("No interfaces found");
                return $this->getEmptyBandwidthStats();
            }

            // Find the main interface (usually ether1 or bridge1)
            $interface = null;
            foreach ($interfaces as $iface) {
                if (isset($iface['name']) && ($iface['name'] === 'ether1' || $iface['name'] === 'bridge1')) {
                    $interface = $iface;
                    break;
                }
            }

            if (!$interface) {
                Log::error("Main interface not found");
                return $this->getEmptyBandwidthStats();
            }

            // Get traffic statistics
            $query = new Query('/interface/monitor-traffic');
            $query->equal('interface', $interface['name']);
            $query->equal('once', '');
            
            sleep(1); // Wait for 1 second to get accurate per-second readings
            
            $response = $this->client->query($query)->read();
            
            if (isset($response[0])) {
                $rx_bits = $response[0]['rx-bits-per-second'] ?? 0;
                $tx_bits = $response[0]['tx-bits-per-second'] ?? 0;
                
                Log::info("Interface {$interface['name']} bandwidth - RX: {$rx_bits} bits/s, TX: {$tx_bits} bits/s");
                
                return [
                    'rx_rate' => $this->formatBytes($rx_bits / 8) . '/s',
                    'tx_rate' => $this->formatBytes($tx_bits / 8) . '/s',
                    'total_rate' => $this->formatBytes(($rx_bits + $tx_bits) / 8) . '/s'
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