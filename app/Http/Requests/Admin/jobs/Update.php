<?php

namespace App\Http\Requests\Admin\jobs;

use App\Http\Requests\BaseRequest;

class Update extends BaseRequest
{
    public function rules()
    {
        return [
            'title'         => 'required|array',
            'title.*'       => 'required|string|max:255|language_match',
            'sub_title'     => 'required|array',
            'sub_title.*'   => 'required|string|max:255|language_match',
            'description'   => 'required|array',
            'description.*' => 'required|string|max:32000',
            'image'         => 'nullable|image|mimes:jpg,jpeg,png',
            'is_active'     => 'required|in:1,0',
        ];
    }
}
