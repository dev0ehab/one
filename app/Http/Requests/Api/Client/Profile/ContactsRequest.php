<?php

namespace App\Http\Requests\Api\Client\Profile;

use App\Enums\ContactType;
use App\Http\Requests\BaseRequest;

class ContactsRequest extends BaseRequest
{
    public function rules()
    {
        $rules = [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:15',
            'country_code' => 'nullable|string|max:10',
            'city_id' => 'sometimes|exists:cities,id',
            'message' => 'required|string',

            'title' => 'sometimes|string',
            'type' => 'sometimes|string|in:' . ContactType::COMPLAINT . ',' . ContactType::SUGGESTION,

            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10000',
            'contactable_id' => 'sometimes',
            'contactable_type' => 'sometimes',
        ];

        if (!auth()->check()) {
            $rules['name']         = 'required|string|max:255';
            $rules['phone']        = 'required|string|max:15';
            $rules['country_code'] = 'required|string|max:10';
            $rules['email']        = 'required|email|max:255';
            $rules['city_id']      = 'required|exists:cities,id';
        }

        return $rules;
    }


    public function prepareForValidation()
    {
        if (auth()->check()) {
            $user = auth()->user();
            $this->merge([
                'name'             => $user->name,
                'phone'            => $user->phone,
                'country_code'     => $user->country_code,
                'email'            => $user->email,
                'contactable_id'   => $user->id,
                'contactable_type' => $user->getMorphClass(),
            ]);
        }
    }
}
