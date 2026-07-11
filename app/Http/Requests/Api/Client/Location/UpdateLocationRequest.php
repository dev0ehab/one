<?php

namespace App\Http\Requests\Api\Client\Location;

use App\Http\Requests\BaseRequest;
use App\Models\Location;

class UpdateLocationRequest extends BaseRequest
{
    public function authorize()
    {
        $modelId = $this->route('location');
        $model = Location::find($modelId);
        return $model && $model->client_id == auth()->id();
    }

    public function rules()
    {
        return [
            'name'             => 'sometimes|string|max:255',
            'lat'              => 'sometimes|numeric',
            'lng'              => 'sometimes|numeric',
            'map_desc'         => 'sometimes|string|max:500',
            'country_id'       => 'sometimes|exists:countries,id',
            'city_id'          => 'sometimes|exists:cities,id',
            'is_main_location' => 'sometimes|boolean',
        ];
    }
}

