<?php

namespace App\Http\Resources\Api\Client\Providers;

use App\Http\Resources\Api\Settings\CategoryResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderMapResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'          => $this->id,
            'trade_name'    => $this->trade_name,
            'image'       => $this->image,
            'avg_rate'        => (float) $this->avg_rate,
            'rate_percentage' => round((float) $this->avg_rate * 20, 1),
            'total_rates'     => (int) ($this->rates_count ?? $this->total_rates ?? 0),
            'lat'         => $this->lat ? (float) $this->lat : null,
            'lng'         => $this->lng ? (float) $this->lng : null,
            'map_desc'    => $this->map_desc,
            'category'    => CategoryResource::make($this->whenLoaded('category')),
            'distance_km' => isset($this->distance) ? round((float) $this->distance, 2) : null,
            'today_work_hours'   => $this->today_work_hours,
            'today_work_periods' => $this->today_work_periods,
        ];
    }
}
