<?php

namespace App\Http\Requests\Admin\introServices;

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
            'title.ar'       => 'required',
            'title.en'       => 'required',
            'description.ar' => 'required',
            'description.en' => 'required',
        ];
    }
}
