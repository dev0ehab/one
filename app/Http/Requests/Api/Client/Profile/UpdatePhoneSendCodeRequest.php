<?php

namespace App\Http\Requests\Api\Client\Profile;

use App\Http\Requests\BaseRequest;

class UpdatePhoneSendCodeRequest extends BaseRequest
{
    public function rules() {
        // get current route action
        $action = request()->route()->getActionMethod();

        $data = [
            'country_code' => 'required|numeric|digits_between:1,5',
        ];

        $data['phone'] = $action === 'sendCodeToOldPhone' ? 'required|numeric|digits_between:9,10|exists:clients,phone' : 'required|numeric|digits_between:9,10|unique:clients,phone,null,id,deleted_at,NULL|starts_with:5';
        return $data;
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone' => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }
}
