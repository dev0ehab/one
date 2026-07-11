<?php

namespace App\Http\Requests\Api\Client\Auth;

use App\Enums\LoginType;
use App\Http\Requests\BaseRequest;

class LoginRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'country_code' => 'required|numeric|digits_between:1,5',
            'phone'        => 'required|numeric|digits_between:9,10|exists:clients,phone,deleted_at,NULL',
            'password'     => 'required|string',
            'device_type'  => 'required|in:ios,android,web',
            'device_id'    => 'required|max:250',
            'mac_address'  => 'required|max:250',
            'preferred_locale' => 'nullable|in:ar,en',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'way'          => LoginType::NORMAL,
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }
}
