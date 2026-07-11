<?php

namespace App\Http\Resources\Api\Client\Providers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderAdResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'title'       => $this->title,
            'description' => $this->description,
            'start_at'    => $this->start_at?->toDateTimeString(),
            'price'       => $this->price,
            'images'      => $this->whenLoaded('images', fn () => $this->images->pluck('image')),
            'approved_at' => $this->approved_at?->toDateTimeString(),
        ];
    }
}
