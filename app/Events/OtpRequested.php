<?php

namespace App\Events;

use App\Models\AuthOtp;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OtpRequested
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var AuthOtp
     */
    public AuthOtp $otp;
    public $identifierType;
    public $identifier;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(AuthOtp $otp, $identifierType, $identifier = null)
    {
        $this->otp = $otp;
        $this->identifierType = $identifierType;
        $this->identifier = $identifier;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
