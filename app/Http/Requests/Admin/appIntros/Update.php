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
            'title.ar'       => 'required|min:3|max:255',
            'title.en'       => 'required|min:3|max:255',
            'description.ar' => 'required|min:3|max:255',
            'description.en' => 'required|min:3|max:255',
            'image'          => ['nullable', 'image'],
        ];
    }
}
