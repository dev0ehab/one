<?php

namespace App\Http\Resources\Api\Client\Providers;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * List card — matches Figma service presenter list screen.
 */
class ProviderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                  => $this->id,
            'trade_name'          => $this->trade_name,
            'image'               => $this->image,
            'avg_rate'            => (float) $this->avg_rate,
            'rate_percentage'     => round((float) $this->avg_rate * 20, 1),
            'today_work_hours'    => $this->today_work_hours,
            'has_booking_service' => $this->hasActiveBookingService(),
            'has_active_booking'  => (bool) ($this->has_active_booking ?? false),
            'is_favorite'         => (bool) ($this->is_favorite ?? false),
            'location'            => [
                'lat'      => $this->lat ? (float) $this->lat : null,
                'lng'      => $this->lng ? (float) $this->lng : null,
                'map_desc' => $this->map_desc,
            ],
            'distance_km'         => isset($this->distance)
                ? round((float) $this->distance, 2)
                : null,
        ];
    }
}
