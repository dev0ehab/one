<?php

namespace App\Http\Requests\Api\Client\Wallet;

use App\Enums\OTPType;
use App\Models\Wallet;
use App\Traits\GeneralTrait;
use App\Http\Requests\BaseRequest;

class CheckCodeRequest extends BaseRequest
{
  use GeneralTrait;

  public function rules(): array
  {
    return [
      'code'         => 'required|numeric|digits:6',
    ];
  }

  public function withValidator($validator): void
  {
    $validator->after(function ($validator) {
      $user = auth()->user();

      if (!$user) {
        $validator->errors()->add('not_user', trans('auth.failed'));
      }

      if (!$this->isCodeCorrect($this->code, OTPType::TRANSFER_CODE, $user)) {
        $validator->errors()->add('wrong_code', trans('auth.code_invalid'));
      }

      if ($this->isCodeExpired($this->code, OTPType::TRANSFER_CODE, $user)) {
        $validator->errors()->add('wrong_code', trans('auth.code_expired'));
      }
    });
  }
}
