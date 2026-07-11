<?php

namespace App\Http\Requests\Api\Provider\Profile;

use App\Http\Requests\BaseRequest;

class UpdatePhoneSendCodeRequest extends BaseRequest
{
    public function rules()
    {
        // get current route action
        $action = request()->route()->getActionMethod();


        $data = [
            'country_code' => 'required|numeric|digits_between:1,5',
        ];

        $data['phone'] = $action === 'sendCodeToOldPhone'
            ? 'required|numeric|digits_between:9,10|exists:providers,phone'
            : 'required|numeric|digits_between:9,10|unique:providers,phone,null,id,deleted_at,NULL|starts_with:5';
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
