<?php

namespace App\Listeners;

use App\Events\OtpRequested;

class SendOtp
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OtpRequested  $event
     * @return void
     */
    public function handle(OtpRequested $event)
    {
        $entity = $event->otp->otpable;
        $code = $event->otp->code;

        if ($event->identifierType == "phone") {
            // send otp on sms
            $entity->sendCodeAtSms($code, $event?->identifier);
        } else {
            // send otp on email
            $entity->sendCodeAtEmail($code, $event?->identifier);
        }
    }
}
