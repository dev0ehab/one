<?php

namespace App\Http\Requests\Admin\auth;

use Illuminate\Foundation\Http\FormRequest;

class loginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email'    => 'required|exists:admins,email',
            'password' => 'required|min:6',
        ];
    }

    public function messages()
    {
        return [
            'email.exists'    => __('admin.wrong credentials'),
        ];
    }
}
