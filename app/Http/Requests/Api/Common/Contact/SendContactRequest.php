<?php

namespace App\Http\Requests\Api\Common\Contact;

use App\Enums\ContactType;
use App\Http\Requests\Api\BaseApiRequest;

class SendContactRequest extends BaseApiRequest
{
    public function prepareForValidation()
    {
        if (auth()->check()) {
            $this->merge([
                'contactable_id'   => auth()->id(),
                'contactable_type' => get_class(auth()->user()),
            ]);
        }

        $this->merge([
            'type' => ContactType::CONTACT,
        ]);
    }

    public function rules()
    {
        return [
            'name'             => ['required', 'string', 'max:50'],
            'country_code'     => ['required', 'string', 'max:10'],
            'phone'            => ['required', 'string', 'min:8',  'max:15'],
            'message'          => ['required', 'string'],
            'type'             => ['sometimes', 'in:'  . ContactType::all()],
            'contactable_id'   => ['sometimes'],
            'contactable_type' => ['sometimes'],

        ];
    }
}
