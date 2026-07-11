<?php

namespace App\Jobs;

use App\Traits\FirebaseTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendFirebaseNotificationToMultipleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, FirebaseTrait;

    private $notifiable = null;

    private $data = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($notifiable, $data)
    {
        $this->notifiable = $notifiable;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->notifiable->is_notify) {
            $this->sendFirebaseNotification($this->notifiable);
        }
    }
}
