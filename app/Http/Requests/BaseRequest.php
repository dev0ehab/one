<?php

namespace App\Http\Requests;

use App\Traits\ResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class BaseRequest extends FormRequest
{
    use ResponseTrait;

    protected function failedValidation(Validator $validator)
    {
        if (request()->is('api/*')) {
            // Don't throw an exception here; let the framework handle it.
            // This will propagate to the Handler class.
            parent::failedValidation($validator);
        } else {
            throw (new ValidationException($validator))
                ->errorBag($this->errorBag)
                ->redirectTo($this->getRedirectUrl());
        }
    }



    public function messages()
    {
        return __('validation');
    }
}
