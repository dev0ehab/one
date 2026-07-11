<?php

namespace App\Http\Resources\Api\Client\Ad;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class AdsCollection extends ResourceCollection
{
    use PaginationTrait;

    public $collects = AdResource::class;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'data'       => AdResource::collection($this->collection),
            'pagination' => $this->paginationModel($this),
        ];
    }
}
