<?php

namespace App\Http\Resources\Api\Provider\Package;

use Illuminate\Http\Resources\Json\JsonResource;

class PackageResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'description'  => $this->description,
            'price'        => $this->price,
            'ads_count'    => $this->ads_count,
            'duration_days'=> $this->duration_days,
        ];
    }
}
