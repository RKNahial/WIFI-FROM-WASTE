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
            $routerUsage = $this->mikrotik->getRouterUsage();
            
            // Get all devices from database
            $devices = Device::orderBy('last_seen', 'desc')->get();
            $activeUsersCount = $devices->where('status', 'Active')->count();

            // Debug logging
            Log::info('Router Usage Data:', $routerUsage);
            Log::info('Active Users:', ['count' => $activeUsersCount, 'data' => $activeUsers]);

            return view('devices.WifiFromWaste', [
                'devices' => $devices,
                'activeUsers' => $activeUsers['data'],
                'activeUsersCount' => $activeUsersCount,
                'routerUsage' => $routerUsage,
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
                'routerUsage' => [
                    'total_usage' => '0 B',
                    'active_users_count' => 0,
                    'last_reset' => now()->format('M j, Y h:i A')
                ],
                'bottleStats' => ['total' => 0, 'today' => 0]
            ]);
        }
    }
}