<?php

namespace App\Http\Requests\Api\Client\Auth;

use App\Http\Requests\BaseRequest;

class DeleteAccountRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'reason' => 'required|string|min:3|max:1000',
        ];
    }
}
