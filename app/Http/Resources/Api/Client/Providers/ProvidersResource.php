<?php

namespace App\Http\Resources\Api\Client\Providers;

use App\Http\Resources\Api\Settings\CategoryResource;
use App\Http\Resources\Api\Settings\CityResource;
use App\Http\Resources\Api\Provider\WorkDays\WorkDaysCollection;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Provider profile — full details for the service presenter screen.
 */
class ProvidersResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                         => $this->id,
            'trade_name'                 => $this->trade_name,
            'owner_name'                 => $this->owner_name,
            'image'                      => $this->image,
            'banners'                    => $this->banner_urls,
            'avg_rate'                   => (float) $this->avg_rate,
            'rate_percentage'            => round((float) $this->avg_rate * 20, 1),
            'total_rates'                => (int) ($this->rates_count ?? $this->total_rates ?? 0),
            'is_open'                    => $this->is_open,
            'today_work_hours'           => $this->today_work_hours,
            'today_work_periods'         => $this->today_work_periods,
            'has_booking_service'        => $this->hasActiveBookingService(),
            'has_active_booking'         => (bool) ($this->has_active_booking ?? false),
            'service_duration_minutes'   => $this->service_duration_minutes,
            'booking_notes'              => $this->booking_notes,
            'is_favorite'                => (bool) ($this->is_favorite ?? false),
            'category'                   => CategoryResource::make($this->whenLoaded('category')),
            'city'                       => CityResource::make($this->whenLoaded('city')),
            'location'                   => [
                'lat'      => $this->lat ? (float) $this->lat : null,
                'lng'      => $this->lng ? (float) $this->lng : null,
                'map_desc' => $this->map_desc,
            ],
            'distance_km'                => isset($this->distance)
                ? round((float) $this->distance, 2)
                : null,
            'contact'                    => [
                'phone'    => $this->phone,
                'whatsapp' => $this->phone,
                'email'    => $this->email,
            ],
            'work_days'                  => WorkDaysCollection::make($this->whenLoaded('workDays')),
            'commercial_register_num'    => $this->commercial_register_num,
            'national_id'                => $this->national_id,
        ];
    }
}
