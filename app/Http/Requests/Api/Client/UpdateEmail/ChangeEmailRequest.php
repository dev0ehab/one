<?php

namespace App\Http\Requests\Api\Client\UpdateEmail;

use App\Http\Requests\BaseRequest;

class ChangeEmailRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'email'         => 'required|email|unique:clients,email,null,id,deleted_at,NULL',
        ];
    }
} 