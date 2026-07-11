<?php

namespace App\Notifications;

use App\Jobs\SendFirebaseNotificationToMultipleJob;
use App\Traits\FirebaseV1Trait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BaseNotification extends Notification
{
    use Queueable, FirebaseV1Trait;

    public $data;

    public function via(mixed $notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        SendFirebaseNotificationToMultipleJob::dispatch($notifiable, $this->data);
        return $this->data;
    }
}
