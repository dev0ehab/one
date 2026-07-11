<?php

namespace App\Http\Requests\Admin\intros;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    public function rules()
    {
        return [
            'title.ar'       => 'required',
            'title.en'       => 'required',
            'description.ar' => 'required',
            'description.en' => 'required',
            'image'          => ['required', 'image'],
        ];
    }
}
