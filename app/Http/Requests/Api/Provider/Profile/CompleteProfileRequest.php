<?php

namespace App\Http\Requests\Api\Provider\Profile;

use App\Http\Requests\BaseRequest;

class CompleteProfileRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name'                      => 'required|max:20|min:3',
            'email'                     => 'nullable|email|unique:providers,email',
            'image'                     => 'required|image|mimes:jpg,jpeg,png',
            'commercial_register_image' => 'required|image|mimes:jpg,jpeg,png',
            'commercial_register_num'   => 'required|numeric|digits_between:10,16',
            'is_completed'              => 'required|boolean',
            'bank_name'                 => 'required|string|max:20|min:3',
            'account_name'              => 'required|string|max:20|min:3',
            'account_num'               => 'required|numeric|digits_between:10,16',
            'iban'                      => 'required|string|max:24|min:24|regex:/^[A-Z]{2}[0-9]{22}$/',
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([
            "is_completed" => true,
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {

            if ($validator->errors()->count() > 0) {
                return;
            }

            if (auth()->user()->is_completed) {
                return $validator->errors()->add('error', __('auth.profile_already_completed'));
            }
        });
    }



}