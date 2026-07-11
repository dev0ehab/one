<?php

namespace App\Http\Requests\Admin\pages;

use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    public function rules()
    {
        return [
            'title'     => 'required|array',
            'title.*'   => 'required|string',
            'content'   => 'required|array',
            'content.*' => 'required|string',
            'image'=> 'nullable|image|mimes:jpg,jpeg,png',
        ];
    }
}
