<?php

namespace App\Http\Resources\Api\Client\Providers;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class ProvidersCollection extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        if ($request->has('is_paginated') && !$request->is_paginated) {
            return ProviderResource::collection($this->collection);
        }

        return [
            'data' => ProviderResource::collection($this->collection),
            'pagination' => $this->paginationModel($this),
        ];
    }
}

