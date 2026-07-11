<?php

namespace App\Notifications;

use App\Traits\FirebaseV1Trait;
use Illuminate\Notifications\Notification;
use Illuminate\Bus\Queueable;

class GlobalNotification extends Notification
{
    use Queueable, FirebaseV1Trait;

    private $data;

    public function __construct($model_type, $model_id, $title_ar, $title_en, $body_ar, $body_en, $type)
    {
        $this->data = [
            'model_type' => $model_type,
            'model_id'   => $model_id,
            'title_ar'   => $title_ar,
            'title_en'   => $title_en,
            'body_ar'    => $body_ar,
            'body_en'    => $body_en,
            'type'       => $type
        ];

    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        if ($notifiable->is_notify) {
            $this->sendFirebaseNotification($notifiable);
        }
        return $this->data;
    }
}
