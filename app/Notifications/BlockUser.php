<?php

namespace App\Notifications;

use App\Traits\FirebaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BlockUser extends Notification
{
    use Queueable,  FirebaseTrait;

    public function via()
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($notifiable->is_notify) {
            $this->sendFcmNotification($notifiable->devices(),  ['type' => 'block'], $notifiable->lang ?? 'ar');
        }
        return ['type' => 'block'];
    }
}
