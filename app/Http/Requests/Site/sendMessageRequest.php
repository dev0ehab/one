<?php

namespace App\Http\Requests\Site;

use Illuminate\Foundation\Http\FormRequest;

class sendMessageRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name'    => 'required|min:2|max:20',
            'email'   => 'required|email',
            'phone'   => 'required|digits_between:9,15|numeric',
            'message' => 'required|min:3|max:250',
        ];
    }
}
