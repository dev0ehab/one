<?php

namespace App\Http\Requests\Admin\introHowWorks;

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
            'title.ar' => 'required',
            'title.en' => 'required',
            'image'    => 'nullable|image',
        ];
    }
}
