<?php

namespace App\Http\Requests\Api\Provider\BookingService;

use Illuminate\Foundation\Http\FormRequest;

class SubscribeBookingServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'payment_method' => ['required', 'string', 'in:wallet,online'],
        ];
    }
}
