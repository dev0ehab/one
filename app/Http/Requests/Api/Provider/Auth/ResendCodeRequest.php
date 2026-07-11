<?php

namespace App\Http\Requests\Api\Provider\Auth;

use App\Models\User;
use App\Http\Requests\BaseRequest;
use App\Models\Provider;

class ResendCodeRequest extends BaseRequest
{

  public function rules()
  {
    return [
      'country_code' => 'required|numeric|digits_between:1,5',
      'phone'        => 'required|numeric|digits_between:9,10|exists:providers,phone,deleted_at,NULL',
    ];
  }

  public function prepareForValidation()
  {
    $this->merge([
      'phone'        => fixPhone($this->phone),
      'country_code' => fixPhone($this->country_code),
    ]);
  }

  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      $user = Provider::where(['phone' => $this->phone, 'country_code' => $this->country_code])->first();

      // Check if the user is not found
      if (!$user) {
        $validator->errors()->add('not_user', trans('auth.failed'));
      }

      // Check if the user is already activated
      if ($user && $user->is_active) {
        $validator->errors()->add('active', trans('auth.activated'));
      }

    });
    
  }
}
