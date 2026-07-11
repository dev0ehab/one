<?php

namespace App\Http\Requests\Admin\introFqsCategories;

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
            'title.ar' => 'required',
            'title.en' => 'required',
        ];
    }
}
