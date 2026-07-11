<?php

namespace App\Http\Resources\Api\Client\Booking;

use App\Traits\PaginationTrait;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class BookingsCollection extends ResourceCollection
{
    use PaginationTrait;

    public function __construct($resource, protected array $meta = [])
    {
        parent::__construct($resource);
    }

    public function toArray($request): array|JsonSerializable|Arrayable
    {
        $payload = [
            'data' => BookingResource::collection($this->collection),
        ];

        if ($this->resource instanceof \Illuminate\Pagination\AbstractPaginator) {
            $payload['pagination'] = $this->paginationModel($this);
        }

        if ($this->meta !== []) {
            $payload['meta'] = $this->meta;
        }

        return $payload;
    }
}
