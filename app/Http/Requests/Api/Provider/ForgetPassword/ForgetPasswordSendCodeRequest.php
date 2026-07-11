<?php

namespace App\Http\Requests\Api\Provider\ForgetPassword;

use App\Http\Requests\BaseRequest;

class ForgetPasswordSendCodeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'country_code' => 'required|numeric|digits_between:1,5',
            'phone'        => 'required|numeric|digits_between:9,10|exists:providers,phone,deleted_at,NULL',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }
}
