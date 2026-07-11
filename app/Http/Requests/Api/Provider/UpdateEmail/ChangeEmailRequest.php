<?php

namespace App\Http\Requests\Api\Provider\UpdateEmail;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class ChangeEmailRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('providers', 'email')->ignore(auth()->id())->whereNull('deleted_at'),
            ],
        ];
    }
}
