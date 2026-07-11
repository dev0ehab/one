<?php

namespace App\Http\Resources\Api\Settings;

use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'image'      => $this->image ?? null,
            'country'    => $this->country?->name,
            // 'region_id' => $this->region_id,
        ];
    }
}
