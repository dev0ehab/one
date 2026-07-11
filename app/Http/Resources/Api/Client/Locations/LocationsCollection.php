<?php

namespace App\Http\Resources\Api\Client\Locations;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class LocationsCollection extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        if ($request->has('is_paginated') && !$request->is_paginated) {
            return LocationResource::collection($this->collection);
        }

        return [
            'data' => LocationResource::collection($this->collection),
            'pagination' => $this->paginationModel($this),
        ];
    }
}

