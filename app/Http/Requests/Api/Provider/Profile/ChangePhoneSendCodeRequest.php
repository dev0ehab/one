<?php

namespace App\Http\Requests\Api\Provider\Profile;

use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Hash;

class ChangePhoneSendCodeRequest extends BaseRequest
{
    public function rules() {
        return [
            'password'     => 'required',
            'country_code' => 'required|numeric|digits_between:1,5',
            'phone'        => 'required|numeric|digits_between:9,10|unique:providers,phone',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone' => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (!Hash::check($this->password, auth()->user()->password)) {
                $validator->errors()->add('password', trans('auth.incorrect_pass'));
            }
        });
    }
}
