<?php

namespace App\Http\Resources\Api\Client\Ad;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'start_at'    => $this->start_at?->toDateTimeString(),
            'price'       => $this->price,
            'images'      => $this->images->pluck('image'),
            'approved_at' => $this->approved_at?->toDateTimeString(),
            'provider'    => $this->when($this->relationLoaded('provider'), fn () => [
                'id'          => $this->provider->id,
                'name'        => $this->provider->trade_name,
                'image'       => $this->provider->image,
                'avg_rate'    => $this->provider->avg_rate,
            ]),
        ];
    }
}
