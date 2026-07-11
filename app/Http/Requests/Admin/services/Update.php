<?php

namespace App\Http\Requests\Admin\services;

use App\Http\Requests\BaseRequest;

class Update extends BaseRequest
{
    public function rules()
    {
        return [
            'title'         => 'required|array',
            'title.*'       => 'required|string|max:255|language_match',
            'description'   => 'required|array',
            'description.*' => 'required|string|max:32000',
            'image'         => 'sometimes|image|mimes:jpg,jpeg,png',
            'is_active'     => 'required|in:1,0',

            'parts'         => 'required|array',
            'parts.*.id'    => 'sometimes',
            'parts.*.ar'    => 'required|string|max:255',
            'parts.*.en'    => 'required|string|max:255',
            'parts.*.image' => 'sometimes|image|mimes:jpg,jpeg,png',
            
            'importants'      => 'required|array',
            'importants.*.id' => 'sometimes',
            'importants.*.ar' => 'required|string|max:255|language_match:ar',
            'importants.*.en' => 'required|string|max:255|language_match:en',
        ];
    }
}
