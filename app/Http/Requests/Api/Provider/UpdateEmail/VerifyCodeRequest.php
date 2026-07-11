<?php

namespace App\Http\Requests\Api\Provider\UpdateEmail;

use App\Http\Requests\BaseRequest;

class VerifyCodeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'code'  => 'required|numeric|digits:6',
        ];
    }
}
