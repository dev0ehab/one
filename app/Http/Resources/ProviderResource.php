<?php

namespace App\Http\Resources;

use App\Http\Resources\Api\Provider\WorkDays\WorkDaysCollection;
use App\Http\Resources\Api\Settings\CategoryResource;
use App\Http\Resources\Api\Settings\CityResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProviderResource extends JsonResource
{
    private string $token = '';

    public function setToken($value): static
    {
        $this->token = $value;

        return $this;
    }

    public function toArray($request): array
    {
        return [
            'id'                         => $this->id,
            'trade_name'                 => $this->trade_name,
            'owner_name'                 => $this->owner_name,
            'commercial_register_num'    => $this->commercial_register_num,
            'image'                      => $this->image,
            'has_booking_service'        => $this->hasActiveBookingService(),
            'booking_service_expires_at' => $this->booking_service_expires_at?->toDateTimeString(),
            'service_duration_minutes'   => $this->service_duration_minutes,
            'booking_notes'              => $this->booking_notes,
            'booking_reminder_minutes'   => $this->booking_reminder_minutes,
            'has_active_subscription'    => (bool) $this->has_active_subscription,
            'has_pending_update_request' => (bool) ($this->has_pending_update_request ?? false),
            'owner'                      => [
                'name'                    => $this->owner_name,
                'phone'                   => $this->phone,
                'country_code'            => $this->country_code,
                'email'                   => $this->email,
                'national_id'             => $this->national_id,
                'commercial_register_num' => $this->commercial_register_num,
            ],
            'location'                   => [
                'lat'      => $this->lat ? (float) $this->lat : null,
                'lng'      => $this->lng ? (float) $this->lng : null,
                'map_desc' => $this->map_desc,
                'city'     => CityResource::make($this->whenLoaded('city')),
            ],
            'start_time'                 => $this->start_time?->format('H:i'),
            'end_time'                   => $this->end_time?->format('H:i'),
            'banners'                    => $this->banner_urls,
            'contact'                    => [
                'phone' => $this->phone,
                'email' => $this->email,
            ],
            'bank'                       => [
                'name'         => $this->bank_name,
                'account_name' => $this->account_name,
                'account_num'  => $this->account_num,
                'iban'         => $this->iban,
            ],
            'category'                   => CategoryResource::make($this->whenLoaded('category')),
            'work_days'                  => WorkDaysCollection::make($this->whenLoaded('workDays')),
            'token'                      => $this->token,
            'room_id'                    => $this->chat?->id,
            'is_active'                  => (bool) $this->is_active,
            'is_approved'                => $this->is_approved,
            'rejection_reason'           => $this->rejection_reason,
            'is_notify'                  => (bool) $this->is_notify,
            'avg_rate'                   => (float) $this->avg_rate,
        ];
    }
}
