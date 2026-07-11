<?php

namespace App\Jobs;

use Illuminate\Queue\{SerializesModels, InteractsWithQueue};
use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Bus\Queueable;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $notifiables;
    public $model;
    public $model_id;
    public $notificationClass;
    public $title_ar;
    public $title_en;
    public $body_ar;
    public $body_en;
    public $type;

    public function __construct($notifiables, $model, $model_id, string $notificationClass, string $title_ar, string $title_en, string $body_ar, string $body_en, $type)
    {
        $this->notifiables       = $notifiables;
        $this->model             = $model;
        $this->model_id          = $model_id;
        $this->notificationClass = $notificationClass;
        $this->title_ar          = $title_ar;
        $this->title_en          = $title_en;
        $this->body_ar           = $body_ar;
        $this->body_en           = $body_en;
        $this->type              = $type;
    }

    public function handle()
    {

        $notification = new $this->notificationClass(
            $this->model,
            $this->model_id,
            $this->title_ar,
            $this->title_en,
            $this->body_ar,
            $this->body_en,
            $this->type
        );

        Notification::send($this->notifiables, $notification);
    }
}
