<?php

namespace App\Http\Requests\Api\Provider\Booking;

use App\Http\Requests\BaseRequest;

class RejectBookingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'reason' => 'nullable|string|max:1000',
        ];
    }
}
