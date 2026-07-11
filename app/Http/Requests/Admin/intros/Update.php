<?php

namespace App\Http\Requests\Admin\intros;

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
            'title.ar'       => 'required',
            'title.en'       => 'required',
            'description.ar' => 'required',
            'description.en' => 'required',
            'image'          => ['nullable', 'image'],
        ];
    }
}
