<?php

namespace App\Http\Requests\Api\Client\Location;

use App\Http\Requests\BaseRequest;

class StoreLocationRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'name'             => 'required|string|max:255',
            'lat'              => 'required|numeric|between:-90,90',
            'lng'              => 'required|numeric|between:-180,180',
            'map_desc'         => 'required|string|max:500',
            'country_id'       => 'required|exists:countries,id',
            'city_id'          => 'required|exists:cities,id',
            'is_main_location' => 'required|boolean',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'is_main_location'    => true
        ]);
    }
}
