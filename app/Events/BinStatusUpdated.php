<?php

namespace App\Events;

use App\Models\BinStatus;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class BinStatusUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $binStatus;
    public $notificationCount;

    public function __construct($binStatus, $notificationCount)
    {
        $this->binStatus = $binStatus;
        $this->notificationCount = $notificationCount;
    }

    public function broadcastOn()
    {
        return new Channel('bin-status');
    }
}