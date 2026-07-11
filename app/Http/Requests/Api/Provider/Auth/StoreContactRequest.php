<?php

namespace App\Http\Requests\Api\Provider\Auth;

use App\Enums\ContactType;
use App\Http\Requests\Api\BaseApiRequest;

class StoreContactRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name'         => 'required|max:50',
            'country_code' => 'required|exists:countries,code',
            'phone'        => 'required|max:20|min:3',
            'email'        => 'nullable|email',
            'title'        => 'required|max:50',
            'message'      => 'required',
            // 'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
            'type'          => 'sometimes|string|in:' . ContactType::COMPLAINT . ',' . ContactType::SUGGESTION,
        ];
    }
}
