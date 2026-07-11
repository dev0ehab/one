<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NotifyAdmin extends Notification
{
    use Queueable;
    
    private $MessageData;

    public function __construct($MessageData)
    {
        $this->MessageData = $MessageData;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return $this->MessageData ;
    }
}
