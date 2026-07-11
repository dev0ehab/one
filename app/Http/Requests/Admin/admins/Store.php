<?php

namespace App\Http\Requests\Admin\admins;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public function rules()
    {
        return [
            'name'     => 'required|max:20|min:3',
            'phone'    => 'required|numeric|digits_between:9,10|unique:admins,phone',
            'email'    => 'required|email|max:191|unique:admins,email',
            'password' => 'required|min:6|max:255',
            'image'    => 'nullable|image',
            'role_id'  => 'required|exists:roles,id',
        ];
    }



    public function prepareForValidation()
    {
        $this->merge([
            'phone' => fixPhone($this->input('phone')),
        ]);
    }

}
