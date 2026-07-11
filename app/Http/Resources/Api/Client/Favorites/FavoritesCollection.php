<?php

namespace App\Http\Resources\Api\Client\Favorites;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class FavoritesCollection extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        if ($request->has('is_paginated') && !$request->is_paginated) {
            return FavoriteResource::collection($this->collection);
        }

        return [
            'data' => FavoriteResource::collection($this->collection),
            'pagination' => $this->paginationModel($this),
        ];
    }
}

