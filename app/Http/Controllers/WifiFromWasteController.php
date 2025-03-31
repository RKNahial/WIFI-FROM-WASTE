<?php

namespace App\Http\Controllers;

use App\Services\MikrotikService;
use App\Models\Device;
use Illuminate\Support\Facades\Log;
use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\BinFullNotification;
use App\Models\BinStatus;
use App\Events\BinStatusUpdated;
use Illuminate\Support\Facades\DB;

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
            $routerUsage = $this->mikrotik->getRouterUsage();
            
            // Get all devices from database
            $devices = Device::orderBy('last_seen', 'desc')->get();
            $activeUsersCount = $devices->where('status', 'Active')->count();

            // Get the latest bin status
            $binStatus = \App\Models\BinStatus::latest('last_checked')->first();

            return view('devices.WifiFromWaste', [
                'devices' => $devices,
                'activeUsers' => $activeUsers['data'],
                'activeUsersCount' => $activeUsersCount,
                'bandwidthStats' => $bandwidthStats,
                'routerUsage' => $routerUsage,
                'bottleStats' => [
                    'total' => 0,
                    'today' => 0
                ],
                'binStatus' => $binStatus,
                'notifications' => DatabaseNotification::where('type', BinFullNotification::class)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function($notification) {
                        return [
                            'id' => $notification->id,
                            'bin_type' => $notification->data['bin_type'],
                            'fill_percentage' => $notification->data['fill_percentage'],
                            'created_at' => $notification->created_at,
                            'read_at' => $notification->read_at,
                            'action_taken' => $notification->data['action_taken'] ?? null,
                            'actioned_by' => $notification->data['actioned_by'] ?? null,
                            'action_timestamp' => $notification->data['action_timestamp'] ?? null
                        ];
                    })
            ]);
        } catch (\Exception $e) {
            Log::error('Error in WifiFromWaste index: ' . $e->getMessage());
            return view('devices.WifiFromWaste', [
                'devices' => collect([]),
                'activeUsers' => ['data' => []],
                'activeUsersCount' => 0,
                'bandwidthStats' => ['total' => '0 B', 'today' => '0 B'],
                'routerUsage' => [
                    'total_usage' => '0 B'
                ],
                'bottleStats' => ['total' => 0, 'today' => 0],
                'binStatus' => null,
                'notifications' => collect([])
            ]);
        }
    }

    public function markNotificationAsRead($id)
    {
        try {
            $notification = DatabaseNotification::findOrFail($id);
            $notification->markAsRead();

            $notificationCount = DatabaseNotification::whereNull('read_at')
                ->where('type', BinFullNotification::class)
                ->count();

            return response()->json([
                'success' => true,
                'notificationCount' => $notificationCount
            ]);

        } catch (\Exception $e) {
            \Log::error('Error marking notification as read: ' . $e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }
}