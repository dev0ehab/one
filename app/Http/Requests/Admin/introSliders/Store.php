<?php

namespace App\Http\Requests\Admin\introSliders;

use Illuminate\Foundation\Http\FormRequest;

class Store extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image'          => 'required|image',
            'title.ar'       => 'required',
            'title.en'       => 'required',
            'description.ar' => 'required',
            'description.en' => 'required',
        ];
    }
}
