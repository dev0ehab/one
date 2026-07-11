<?php

namespace App\Http\Resources\Api\Client\Providers;

use Illuminate\Http\Resources\Json\JsonResource;

class ProviderReviewResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'         => $this->id,
            'rate'       => (int) $this->rate,
            'comment'    => $this->comment,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
            'client'     => $this->when($this->relationLoaded('client'), fn () => [
                'id'    => $this->client->id,
                'name'  => $this->client->name,
                'image' => $this->client->image,
            ]),
        ];
    }
}
