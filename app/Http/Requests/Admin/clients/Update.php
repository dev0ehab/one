<?php

namespace App\Http\Requests\Admin\clients;

use App\Http\Requests\BaseRequest;

class Update extends BaseRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }

    public function rules()
    {
        $clientId = $this->route('id'); // Get client ID from route

        return [
            'image'        => 'nullable|image|mimes:jpg,jpeg,png',
            'name'         => 'required|max:20|min:3',
            'country_code' => 'required|numeric|digits_between:2,5',
            'phone'        => 'required|numeric|digits_between:9,10|unique:clients,phone,' . $clientId . ',id,deleted_at,NULL|starts_with:5',
            'email'        => 'required|email|unique:clients,email,' . $clientId . ',id,deleted_at,NULL',
            'is_blocked'   => 'required|in:0,1',
        ];
    }
}
