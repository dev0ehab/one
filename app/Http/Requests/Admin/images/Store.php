<?php

namespace App\Http\Requests\Admin\images;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public function rules()
    {
        return [
            'image' => ['required', 'image'],
        ];
    }
}
