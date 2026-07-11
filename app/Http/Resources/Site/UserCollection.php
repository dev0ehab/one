<?php

namespace App\Http\Resources\Site;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class UserCollection extends ResourceCollection
{
    use PaginationTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request): array|JsonSerializable|Arrayable
    {
        if ($request->has('is_paginated') && !$request->is_paginated) {
            return UserResource::collection($this->collection);
        }

        return [

            'pagination' => $this->paginationModel($this),
            'data' => UserResource::collection($this->collection),
        ];
    }
}
