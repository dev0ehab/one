<?php

namespace App\Http\Requests\Api\Client\Profile;

use App\Http\Requests\BaseRequest;

class UpdateProfileRequest extends BaseRequest
{
  public function rules(): array
  {
    return [
      'name'  => ['sometimes', 'required', 'string', 'min:3', 'max:50'],
      'image' => ['sometimes', 'image', 'mimes:jpg,jpeg,png'],
    ];
  }
}
