<?php

namespace App\Http\Requests\Api\Client\ForgetPassword;

use App\Http\Requests\BaseRequest;

class ForgetPasswordSendCodeRequest extends BaseRequest
{
    public function rules() {
        return [
            'country_code' => 'required|numeric|digits_between:1,5',
            'phone'        => 'required|numeric|digits_between:9,10|exists:clients,phone,deleted_at,NULL',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone' => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }

}
