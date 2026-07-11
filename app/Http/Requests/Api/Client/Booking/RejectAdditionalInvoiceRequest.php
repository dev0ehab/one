<?php

namespace App\Http\Requests\Api\Client\Booking;

use App\Http\Requests\BaseRequest;

class RejectAdditionalInvoiceRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'reason' => 'nullable|string|max:500',
        ];
    }
}
