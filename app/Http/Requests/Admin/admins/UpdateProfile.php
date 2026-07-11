<?php

namespace App\Http\Requests\Admin\admins;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfile extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'  => 'required|max:20|min:3',
            'phone' => "required|numeric|digits_between:9,10|unique:admins,phone," . auth('admin')->id(),
            'email' => "required|email|max:191|unique:admins,email," . auth('admin')->id(),
            'image' => 'nullable|image',
        ];
    }
}
