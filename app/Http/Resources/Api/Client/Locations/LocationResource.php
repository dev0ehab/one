<?php

namespace App\Http\Resources\Api\Client\Locations;

use Illuminate\Http\Resources\Json\JsonResource;

class LocationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'lat'              => $this->lat,
            'lng'              => $this->lng,
            'map_desc'         => $this->map_desc,
            'country_name'     => $this->country?->name,
            'city_name'        => $this->city?->name,
            'is_main_location' => $this->is_main_location,
        ];
    }
}

