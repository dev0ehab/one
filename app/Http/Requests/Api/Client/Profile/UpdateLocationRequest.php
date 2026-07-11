<?php

namespace App\Http\Requests\Api\Client\Profile;

use App\Http\Requests\BaseRequest;

class UpdateLocationRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'country_id' => 'required|exists:countries,id',
            'city_id'    => 'required|exists:cities,id',
            'lat'        => 'required|numeric|between:-90,90',
            'lng'        => 'required|numeric|between:-180,180',
            'map_desc'   => 'required|string|max:255',
        ];
    }
}
