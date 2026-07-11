<?php

namespace App\Http\Requests\Api\Client\Booking;

use App\Http\Requests\BaseRequest;

class PayBookingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'payment_method' => 'required|in:wallet,online',
        ];
    }
}
