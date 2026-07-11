<?php

namespace App\Http\Resources\Api\Inquiry;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class InquiriesCollection extends ResourceCollection
{
    use PaginationTrait;

    public $collects = InquiryResource::class;

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        $payload = [
            'data' => InquiryResource::collection($this->collection),
        ];

        if ($this->resource instanceof \Illuminate\Pagination\AbstractPaginator) {
            $payload['pagination'] = $this->paginationModel($this);
        }

        return $payload;
    }
}
