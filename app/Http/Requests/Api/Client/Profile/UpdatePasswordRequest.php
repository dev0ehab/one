<?php

namespace App\Http\Requests\Api\Client\Profile;

use App\Http\Requests\BaseRequest;
use App\Overrides\CustomPasswordRule;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordRequest extends BaseRequest
{

  public function rules()
  {
    return [
      'password' => ['required', 'string', CustomPasswordRule::min(8)->letters()->numbers()],
      'old_password' => 'required|min:6|max:100',
    ];
  }

  public function withValidator($validator)
  {
    $validator->after(function ($validator) {
      if ($this->has('old_password') && !Hash::check($this->old_password, auth()->user()->password)) {
        return $validator->errors()->add('old_password', trans('auth.incorrect_old_pass'));
      }

      if ($this->old_password == $this->password) {
        return $validator->errors()->add('password', trans('auth.same_password'));
      }
    });

  }

  

}
