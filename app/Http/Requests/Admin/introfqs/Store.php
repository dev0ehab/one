<?php

namespace App\Http\Requests\Admin\introfqs;

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
            'title.ar'              => 'required',
            'title.en'              => 'required',
            'description.en'        => 'required',
            'description.ar'        => 'required',
            'intro_fqs_category_id' => 'required|exists:intro_fqs_categories,id',
        ];
    }
}
