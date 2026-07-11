<?php

namespace App\Http\Requests\Admin\sliders;

use App\Http\Requests\BaseRequest;

class Store extends BaseRequest
{
    public function rules()
    {
        return [
            'title'         => 'required|array',
            'title.*'       => 'required|string|max:255|language_match',
            'description'   => 'required|array',
            'description.*' => 'required|string|max:32000',
            'image'         => 'required|image|mimes:jpg,jpeg,png',
            'is_active'     => 'required|in:1,0',
        ];
    }
}
