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
use App\Models\MaterialDetection;

class WifiFromWasteController extends Controller
{
    protected $mikrotik;

    private const RATES = [
        'plastic_rate' => 4.00,  // ₱4 per kg for plastic
        'can_rate' => 5.00,     // ₱5 per kg for sardine cans
        'paper_rate' => 0.50    // ₱0.50 per kg for paper
    ];

    private const WEIGHTS = [
        'plastic' => 0.012,    // 12g per plastic bottle (83.33 bottles = 1kg)
        'can' => 0.050,       // 50g per sardine can (20 cans = 1kg)
        'paper' => 0.0045     // 4.5g per A4 sheet (222.22 sheets = 1kg)
    ];

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

            $plasticCount = MaterialDetection::where('material_type', 'plastic')->sum('count');
            $canCount = MaterialDetection::where('material_type', 'can')->sum('count');
            
            // Calculate weights in kg
            $plasticWeight = ($plasticCount * self::WEIGHTS['plastic']);
            $canWeight = ($canCount * self::WEIGHTS['can']);
            
            // Calculate revenue
            $estimatedRevenue = [
                'plastic_rate' => self::RATES['plastic_rate'],
                'can_rate' => self::RATES['can_rate'],
                'paper_rate' => self::RATES['paper_rate'],
                'plastic' => $plasticWeight * self::RATES['plastic_rate'],
                'cans' => $canWeight * self::RATES['can_rate'],
                'paper' => 0,
                'total' => ($plasticWeight * self::RATES['plastic_rate']) + 
                          ($canWeight * self::RATES['can_rate'])
            ];

            return view('devices.WifiFromWaste', [
                'devices' => $devices,
                'activeUsers' => $activeUsers['data'],
                'activeUsersCount' => $activeUsersCount,
                'bandwidthStats' => $bandwidthStats,
                'routerUsage' => $routerUsage,
                'bottleStats' => [
                    'plastic_total' => MaterialDetection::where('material_type', 'plastic')->sum('count'),
                    'can_total' => MaterialDetection::where('material_type', 'can')->sum('count'),
                    'today' => MaterialDetection::whereDate('detected_at', today())->sum('count')
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
                    }),
                'estimatedRevenue' => $estimatedRevenue,
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
                'bottleStats' => ['plastic_total' => 0, 'can_total' => 0, 'today' => 0],
                'binStatus' => null,
                'notifications' => collect([]),
                'estimatedRevenue' => [
                    'plastic_rate' => self::RATES['plastic_rate'],
                    'can_rate' => self::RATES['can_rate'],
                    'paper_rate' => self::RATES['paper_rate'],
                    'plastic' => 0,
                    'cans' => 0,
                    'paper' => 0,
                    'total' => 0
                ],
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