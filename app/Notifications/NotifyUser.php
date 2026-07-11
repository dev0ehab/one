<?php

namespace App\Notifications;

use App\Traits\FirebaseV1Trait;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NotifyUser extends Notification implements ShouldQueue
{
    use Queueable , FirebaseV1Trait;
    private $data;
   
    public function __construct($request)
    {
        $this->data = [
            'body_ar'       => $request['body_ar'] ?? $request['body_form_ar'] ?? $request['body_modal_ar'],
            'body_en'       => $request['body_en'] ?? $request['body_form_en'] ?? $request['body_modal_en'],
            'type'          => 'admin_notify' ,
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
        return $this->data ;
    }
}
