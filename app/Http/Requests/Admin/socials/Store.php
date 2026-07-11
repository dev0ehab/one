<?php

namespace App\Http\Requests\Admin\socials;

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
            'icon' => 'required|image',
            'link' => 'required|url',
            'name' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'link' => __('admin.link')
        ];
    }
}
