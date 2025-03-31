<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class BinFullNotification extends Notification
{
    use Queueable;

    protected $binStatus;

    public function __construct($binStatus)
    {
        $this->binStatus = $binStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Bin Full Alert')
            ->line("The {$this->binStatus->bin_type} bin is now {$this->binStatus->fill_percentage}% full.")
            ->line('Please check and empty the bin.');
    }

    public function toArray($notifiable)
    {
        return [
            'bin_type' => $this->binStatus->bin_type,
            'fill_percentage' => $this->binStatus->fill_percentage,
            'last_checked' => $this->binStatus->last_checked
        ];
    }
}