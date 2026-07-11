<?php

namespace App\Http\Resources\Api\Chat;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class MessagesCollection extends ResourceCollection
{
    use PaginationTrait;

    public $collects = MessageResource::class;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        $payload = [
            'data' => MessageResource::collection($this->collection),
        ];

        if ($this->resource instanceof \Illuminate\Pagination\AbstractPaginator) {
            $payload['pagination'] = $this->paginationModel($this);
        }

        return $payload;
    }
}
