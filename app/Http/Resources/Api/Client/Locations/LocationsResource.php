<?php

namespace App\Http\Resources\Api\Client\Locations;

use App\Http\Resources\Api\Settings\CityResource;
use App\Http\Resources\Api\Settings\CountryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class LocationsResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'lat'              => $this->lat,
            'lng'              => $this->lng,
            'map_desc'         => $this->map_desc,
            'is_main_location' => (bool) $this->is_main_location,
            'country'          => new CountryResource($this->country),
            'city'             => new CityResource($this->city),
        ];
    }
}

