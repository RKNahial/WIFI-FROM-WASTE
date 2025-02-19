<?php

namespace App\Http\Controllers;

use App\Services\MikrotikService;
use App\Models\Device;
use Illuminate\Support\Facades\Log;

class WifiFromWasteController extends Controller
{
    protected $mikrotik;

    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    public function index()
    {
        try {
            $activeUsers = $this->mikrotik->getActiveUsers();
            $bandwidthStats = $this->mikrotik->getBandwidthUsage();
            $routerBandwidth = $this->mikrotik->getRouterBandwidth();
            
            // Get all devices from database
            $devices = Device::orderBy('last_seen', 'desc')->get();
            $activeUsersCount = $devices->where('status', 'Active')->count();

            return view('devices.WifiFromWaste', [
                'devices' => $devices,
                'activeUsers' => $activeUsers['data'],
                'activeUsersCount' => $activeUsersCount,
                'bandwidthStats' => $bandwidthStats,
                'routerBandwidth' => $routerBandwidth,
                'bottleStats' => [
                    'total' => 0,
                    'today' => 0
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in WifiFromWaste index: ' . $e->getMessage());
            return view('devices.WifiFromWaste', [
                'devices' => collect([]),
                'activeUsers' => ['data' => []],
                'activeUsersCount' => 0,
                'bandwidthStats' => ['total' => '0 B', 'today' => '0 B'],
                'routerBandwidth' => ['rx_rate' => '0 B/s', 'tx_rate' => '0 B/s', 'total_rate' => '0 B/s'],
                'bottleStats' => ['total' => 0, 'today' => 0]
            ]);
        }
    }
}