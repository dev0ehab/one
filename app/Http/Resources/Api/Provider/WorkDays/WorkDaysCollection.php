<?php

namespace App\Http\Resources\Api\Provider\WorkDays;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class WorkDaysCollection extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        if (!$this->resource instanceof \Illuminate\Pagination\AbstractPaginator) {
            return WorkDayResource::collection($this->collection);
        }

        if ($request->has('is_paginated') && !$request->is_paginated) {
            return WorkDayResource::collection($this->collection);
        }

        return [
            'data'       => WorkDayResource::collection($this->collection),
            'pagination' => $this->paginationModel($this->resource),
        ];
    }
}
