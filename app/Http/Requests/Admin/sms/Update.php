<?php

namespace App\Http\Requests\Admin\sms;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'sender_name' => 'required',
            'user_name'   => 'required',
            'password'    => 'required',
        ];
    }
}
