<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BinStatus;
use Illuminate\Http\Request;
use App\Notifications\BinFullNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Notifications\DatabaseNotification;
use App\Models\User;
use App\Events\BinStatusUpdated;

class BinSensorController extends Controller
{
    public function updateStatus(Request $request)
    {
        \Log::info('Received bin status update request', $request->all());
        
        try {
            $request->validate([
                'bin_type' => 'required|string|in:plastic,can',
                'fill_percentage' => 'required|integer|min:0|max:100',
            ]);

            // Update bin status
            $binStatus = BinStatus::updateOrCreate(
                ['bin_type' => $request->bin_type],
                [
                    'fill_percentage' => $request->fill_percentage,
                    'is_full' => $request->fill_percentage >= 90,
                    'last_checked' => now(),
                ]
            );

            // Get all admin users
            $admins = User::where('is_admin', true)->get();

            // Only send notification if bin is full
            if ($binStatus->is_full) {
                Notification::send($admins, new BinFullNotification($binStatus));
            }

            // Get all bin statuses
            $binStatuses = [
                'bins' => [
                    'plastic' => BinStatus::where('bin_type', 'plastic')->first()?->toArray(),
                    'can' => BinStatus::where('bin_type', 'can')->first()?->toArray()
                ],
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
            ];

            $notificationCount = DatabaseNotification::whereNull('read_at')
                ->where('type', BinFullNotification::class)
                ->count();

            event(new BinStatusUpdated($binStatuses, $notificationCount));

            return response()->json([
                'success' => true,
                'message' => 'Bin status updated successfully',
                'data' => $binStatus
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Error updating bin status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating bin status',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}