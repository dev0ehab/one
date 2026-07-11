<?php

namespace App\Http\Requests\Api\Client\ForgetPassword;

use App\Enums\OTPType;
use App\Http\Requests\BaseRequest;
use App\Models\Client;
use App\Traits\GeneralTrait;
use App\Overrides\CustomPasswordRule;
use Illuminate\Support\Facades\Hash;


class ResetPasswordCheckCodeRequest extends BaseRequest
{
    use GeneralTrait;

    public function rules()
    {
        return [
            'code'         => 'required|numeric|digits:6',
            'country_code' => 'required|exists:clients,country_code',
            'phone'        => 'required|exists:clients,phone',
            'password'     => ['required', 'string', CustomPasswordRule::min(8)->letters()->numbers()],
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

            if ($client && $client->password && Hash::check($this->password, $client->password)) {
                $validator->errors()->add('same_password', trans('auth.same_password'));
            }
        });
    }
}
