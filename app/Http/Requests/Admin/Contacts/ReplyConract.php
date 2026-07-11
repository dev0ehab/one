<?php

namespace App\Http\Requests\Admin\Contacts;

use Illuminate\Foundation\Http\FormRequest;

class ReplyConract extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reply' => 'required|string|max:500',
        ];
    }
}

