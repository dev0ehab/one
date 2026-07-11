<?php

namespace App\Http\Requests\Api\Client\Auth;

use App\Enums\ContactType;
use App\Http\Requests\Api\BaseApiRequest;

class StoreContactRequest extends BaseApiRequest
{
    public function rules()
    {
        return [
            'name'          => ['required', 'string', 'max:50'],
            'country_code'  => ['required', 'string', 'max:10'],
            'phone'         => ['required', 'string', 'max:15'],
            'message'       => ['required', 'string'],
            'type'          => ['sometimes' , 'in:'  . ContactType::all()],

        ];
    }
}
