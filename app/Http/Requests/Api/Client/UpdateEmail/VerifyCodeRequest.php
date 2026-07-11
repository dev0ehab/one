<?php

namespace App\Http\Requests\Api\Client\UpdateEmail;

use App\Enums\OTPType;
use App\Http\Requests\BaseRequest;
use App\Traits\GeneralTrait;

class VerifyCodeRequest extends BaseRequest
{
    use GeneralTrait;

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'code'  => 'required|numeric|digits:6',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = auth()->user();

            if (!$this->isCodeCorrect($this->code, OTPType::UPDATE_EMAIL, $user)) {
                $validator->errors()->add('code', trans('auth.code_invalid'));
            }

            if ($this->isCodeExpired($this->code, OTPType::UPDATE_EMAIL, $user)) {
                $validator->errors()->add('code', trans('auth.code_expired'));
            }
        });
    }
} 