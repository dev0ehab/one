<?php

namespace App\Http\Requests\Admin\clients;

use App\Http\Requests\BaseRequest;

class Store extends BaseRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
            'is_active'    => 1,
        ]);
    }

    public function rules()
    {
        return [
            'image'        => 'required|image|mimes:jpg,jpeg,png',
            'name'         => 'required|max:20|min:3',
            'country_code' => 'required|numeric|digits_between:2,5',
            'phone'        => 'required|numeric|digits_between:9,10|unique:clients,phone,null,id,deleted_at,NULL|starts_with:5',
            'email'        => 'required|email|unique:clients,email,null,id,deleted_at,NULL',
            'country_id'   => 'required|exists:countries,id',
            'city_id'      => 'required|exists:cities,id',
            'lat'          => 'required|numeric|between:-90,90',
            'lng'          => 'required|numeric|between:-180,180',
            'map_desc'     => 'required|string|max:191',
            'is_blocked'   => 'required|in:0,1',
        ];
    }
}

