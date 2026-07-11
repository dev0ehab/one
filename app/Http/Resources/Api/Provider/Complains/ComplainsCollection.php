<?php

namespace App\Http\Resources\Api\Provider\Complains;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class ComplainsCollection extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        if ($request->has('is_paginated') && !$request->is_paginated) {
            return ComplainResource::collection($this->collection);
        }

        return [
            'data' => ComplainResource::collection($this->collection),
            'pagination' => $this->paginationModel($this),
        ];
    }
}
