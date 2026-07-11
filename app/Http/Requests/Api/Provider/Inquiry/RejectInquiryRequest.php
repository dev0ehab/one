<?php

namespace App\Http\Requests\Api\Provider\Inquiry;

use App\Http\Requests\BaseRequest;

class RejectInquiryRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'reason' => 'nullable|string|max:500',
        ];
    }
}
