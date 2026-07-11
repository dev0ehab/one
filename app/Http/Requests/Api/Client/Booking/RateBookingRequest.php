<?php

namespace App\Http\Requests\Api\Client\Booking;

use App\Http\Requests\BaseRequest;

class RateBookingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'rate'    => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ];
    }
}
