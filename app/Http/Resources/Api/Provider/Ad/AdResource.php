<?php

namespace App\Http\Resources\Api\Provider\Ad;

use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'start_at'     => $this->start_at?->toDateTimeString(),
            'price'        => $this->price,
            'status'       => $this->status,
            'reject_reason'=> $this->reject_reason,
            'images'       => $this->images->pluck('image'),
            'approved_at'  => $this->approved_at?->toDateTimeString(),
            'expires_at'   => $this->expires_at?->toDateTimeString(),
            'views'        => $this->views,
            'clicks'       => $this->clicks,
            'created_at'   => $this->created_at?->toDateTimeString(),
        ];
    }
}
