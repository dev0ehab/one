<?php

namespace App\Http\Resources\Api\Client\Providers;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class ProviderReviewsCollection extends ResourceCollection
{
    use PaginationTrait;

    public $collects = ProviderReviewResource::class;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        return [
            'data'       => ProviderReviewResource::collection($this->collection),
            'pagination' => $this->paginationModel($this),
        ];
    }
}
