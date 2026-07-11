<?php

namespace App\Http\Requests\Api\Provider\Profile;

use App\Http\Requests\BaseRequest;

class UpdateLocationRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'lat'      => 'required|numeric|between:-90,90',
            'lng'      => 'required|numeric|between:-180,180',
            'map_desc' => 'required|string|max:255',
        ];
    }
}
