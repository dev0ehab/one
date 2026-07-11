<?php

namespace App\Http\Resources\Api\Provider\Package;

use Illuminate\Http\Resources\Json\JsonResource;

class SubscriptionResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'             => $this->id,
            'package'        => new PackageResource($this->whenLoaded('package')),
            'paid_amount'    => $this->paid_amount,
            'payment_method' => $this->payment_method,
            'status'         => $this->status,
            'ads_remaining'  => $this->ads_remaining,
            'starts_at'      => $this->starts_at?->toDateTimeString(),
            'ends_at'        => $this->ends_at?->toDateTimeString(),
            'is_active'      => $this->isActive(),
        ];
    }
}
