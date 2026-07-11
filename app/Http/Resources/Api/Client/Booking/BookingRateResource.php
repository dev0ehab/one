<?php

namespace App\Http\Resources\Api\Client\Booking;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingRateResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'rate'       => (int) $this->rate,
            'comment'    => $this->comment,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
