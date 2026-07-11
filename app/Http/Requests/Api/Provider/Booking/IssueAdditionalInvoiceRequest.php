<?php

namespace App\Http\Requests\Api\Provider\Booking;

use App\Http\Requests\BaseRequest;

class IssueAdditionalInvoiceRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:0.01',
            'reason' => 'required|string|min:3|max:1000',
        ];
    }
}
