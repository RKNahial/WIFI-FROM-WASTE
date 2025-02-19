<?php

namespace App\Services;

use RouterOS\Client;
use RouterOS\Config;
use RouterOS\Query;
use Exception;
use Illuminate\Support\Facades\Log;

class MikrotikService
{
    protected $client;
    protected $isConnected = false;
    protected $lastError = '';

    public function __construct()
    {
        $this->connect();
    }

    protected function connect()
    {
        try {
            $config = new Config([
                'host' => config('mikrotik.host'),
                'user' => config('mikrotik.username'),
                'pass' => config('mikrotik.password'),
                'port' => (int)config('mikrotik.port')
            ]);

            $this->client = new Client($config);
            $this->isConnected = true;
        } catch (\Exception $e) {
            $this->isConnected = false;
        }
    }

    public function getActiveUsers()
    {
        if (!$this->isConnected) {
            return ['data' => []];
        }

        try {
            $query = new Query('/ip/hotspot/active/print');
            return ['data' => $this->client->query($query)->read()];
        } catch (\Exception $e) {
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

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}