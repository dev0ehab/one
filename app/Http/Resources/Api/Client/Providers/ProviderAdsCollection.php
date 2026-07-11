<?php

namespace App\Http\Resources\Api\Client\Providers;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class ProviderAdsCollection extends ResourceCollection
{
    use PaginationTrait;

    public $collects = ProviderAdResource::class;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'data'       => ProviderAdResource::collection($this->collection),
            'pagination' => $this->paginationModel($this),
        ];
    }
}
