<?php

namespace App\Http\Requests\Admin\auth;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
{
    public function rules()
    {
        return [
            'old_password' => 'required|min:6|different:password',
            'password' => 'required|min:6|confirmed|different:old_password',
        ];
    }
}
