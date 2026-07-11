<?php

namespace App\Http\Requests\Api\Client\ForgetPassword;

use App\Enums\OTPType;
use App\Models\Client;
use App\Http\Requests\BaseRequest;
use App\Traits\GeneralTrait;

class ForgetPasswordCheckCodeRequest extends BaseRequest
{
    use GeneralTrait;

    public function rules()
    {
        return [
            'code'         => 'required|numeric|digits:6',
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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $client = Client::where(['phone' => $this->phone, 'country_code' => $this->country_code])->first();
            if (!$client) {
                $validator->errors()->add('not_user', trans('auth.failed'));
            }

            if (!$this->isCodeCorrect($this->code, OTPType::FORGET_PASSWORD, $client)) {
                $validator->errors()->add('wrong_code', trans('auth.code_invalid'));
            }

            if ($this->isCodeExpired($this->code, OTPType::FORGET_PASSWORD, $client)) {
                $validator->errors()->add('wrong_code', trans('auth.code_expired'));
            }
        });
    }
}
