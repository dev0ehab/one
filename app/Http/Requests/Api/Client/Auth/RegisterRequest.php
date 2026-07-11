<?php

namespace App\Http\Requests\Api\Client\Auth;

use App\Enums\LoginType;
use App\Http\Requests\BaseRequest;
use App\Overrides\CustomPasswordRule;
use Illuminate\Support\Arr;

class RegisterRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'name'                   => 'required|string|min:3|max:50',
            'country_code'           => 'required|numeric|digits_between:2,5',
            'phone'                  => 'required|numeric|digits_between:9,10|unique:clients,phone,null,id,deleted_at,NULL|starts_with:5',
            'password'               => ['required', 'string', 'confirmed', CustomPasswordRule::min(8)->letters()->numbers()],
            'accept_terms'           => 'required|accepted',
            'image'                  => 'nullable|image|mimes:jpg,jpeg,png',
            'country_id'             => 'nullable|exists:countries,id',
            'city_id'                => 'nullable|exists:cities,id',
            'lat'                    => 'nullable|numeric|between:-90,90',
            'lng'                    => 'nullable|numeric|between:-180,180',
            'map_desc'               => 'nullable|string|max:191',
        ];
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
            'is_active'    => 0,
            'way'          => LoginType::NORMAL,
        ]);
    }

    public function validated($key = null, $default = null)
    {
        return Arr::except(parent::validated($key, $default), [
            'password_confirmation',
            'accept_terms',
        ]);
    }
}
