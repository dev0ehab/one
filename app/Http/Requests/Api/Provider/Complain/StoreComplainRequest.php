<?php

namespace App\Http\Requests\Api\Provider\Complain;

use App\Http\Requests\BaseRequest;

class StoreComplainRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string|min:10|max:1000',
            'images'      => 'nullable|array|max:5',
            'images.*'    => 'image|mimes:jpeg,png,jpg,gif,svg|max:10000',
        ];
    }
}
