<?php

namespace App\Http\Controllers;

use App\Services\MikrotikService;
use App\Models\Device;

class WifiFromWasteController extends Controller
{
    protected $mikrotik;

    public function __construct(MikrotikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    public function index()
    {
        $devices = Device::all();
        $activeUsers = $this->mikrotik->getActiveUsers();
        $bandwidthStats = $this->mikrotik->getBandwidthUsage();

        return view('devices.WifiFromWaste', [
            'devices' => $devices,
            'activeUsers' => $activeUsers['data'],
            'bandwidthStats' => $bandwidthStats,
            'bottleStats' => [
                'total' => 0,
                'today' => 0
            ]
        ]);
    }
}