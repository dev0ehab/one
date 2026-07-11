<?php

namespace App\Http\Requests\Admin\admins;

use Illuminate\Foundation\Http\FormRequest;
use App\Overrides\CustomPasswordRule;


class Update extends FormRequest
{
    public function rules(): array
    {
        return [
            'name'       => 'required|max:20|min:3',
            'phone'      => "required|numeric|digits_between:9,10|unique:admins,phone," . $this->id,
            'email'      => "required|email|max:191|unique:admins,email," . $this->id,
            'password'   => ['nullable', 'string', CustomPasswordRule::min(8)->letters()->numbers()],
            'image'      => 'nullable|image',
            'role_id'    => 'required|exists:roles,id',
            'active'     => 'nullable|in:1,0',
            'is_blocked' => 'required|in:1,0',
        ];
    }



    public function withValidator($validator){
        $validator->after(function ($validator) {
            if ($this->id == 1) {
                $validator->errors()->add('err', 'لا يمكن تعديل الادمن الرئيسي');
            }
        });
    }

}
