<?php

namespace App\Http\Requests\Api\Client\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ChangePhoneCheckCodeRequest extends FormRequest
{
    public function rules() {
        return [
            'code'         => 'required',
            'country_code' => 'required|numeric|digits_between:1,5',
            'phone'        => 'required|numeric|digits_between:9,10|unique:clients,phone',
        ];
    }
}
