<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;
use App\Notifications\BinFullNotification;

class CleanupOldNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Mark old notifications as read';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = DatabaseNotification::where('type', BinFullNotification::class)
            ->whereNull('read_at')
            ->where('created_at', '<', now()->subHours(24))
            ->update(['read_at' => now()]);

        $this->info("Marked {$count} old notifications as read");
    }
}
