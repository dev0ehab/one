<?php

namespace App\Http\Resources\Api\Provider\Rates;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class RatesCollection extends ResourceCollection
{
    use PaginationTrait;

    public $collects = RateResource::class;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'data'       => RateResource::collection($this->collection),
            'pagination' => $this->paginationModel($this),
        ];
    }
}
