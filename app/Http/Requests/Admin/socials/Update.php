<?php

namespace App\Http\Requests\Admin\socials;

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
            'icon' => 'nullable|image',
            'link' => 'required|url',
            'name' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'link' => __('admin.link')
        ];
    }
}
