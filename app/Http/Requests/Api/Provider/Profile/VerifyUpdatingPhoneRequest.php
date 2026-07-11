<?php

namespace App\Http\Requests\Api\Provider\Profile;

use Illuminate\Foundation\Http\FormRequest;

class VerifyUpdatingPhoneRequest extends FormRequest
{
    public function rules() {
        // get current route action
        $action = request()->route()->getActionMethod();

        $data = [
            'code'         => 'required',
            'country_code' => 'required|numeric|digits_between:1,5',
        ];

        $data['phone'] = $action === 'verifyNewPhone' ? 'required|numeric|digits_between:9,10' : 'required|numeric|digits_between:9,10|exists:providers,phone';
        return $data;
    }
}
