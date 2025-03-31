<?php

namespace App\Services;

use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\BinFullNotification;

class NotificationCleanupService
{
    public static function cleanup()
    {
        return DatabaseNotification::where('type', BinFullNotification::class)
            ->whereNull('read_at')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['read_at' => now()]);
    }
}