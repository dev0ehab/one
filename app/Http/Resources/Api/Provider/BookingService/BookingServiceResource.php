<?php

namespace App\Http\Resources\Api\Provider\BookingService;

use Illuminate\Http\Resources\Json\JsonResource;

class BookingServiceResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'has_booking_service'        => $this->hasActiveBookingService(),
            'booking_service_expires_at' => $this->booking_service_expires_at?->toDateTimeString(),
            'service_duration_minutes'   => $this->service_duration_minutes,
            'booking_notes'              => $this->booking_notes,
            'booking_reminder_minutes'   => $this->booking_reminder_minutes,
            'show_in_offers'             => (bool) $this->show_in_offers,
            'discount_percentage'        => $this->discount_percentage !== null ? (float) $this->discount_percentage : null,
            'cancellation_policy'        => $this->cancellation_policy,
            'monthly_fee'                => (float) (settings()['booking_service_monthly_fee'] ?? 0),
            'duration_days'              => max(1, (int) (settings()['booking_service_duration_days'] ?? 30)),
            'work_days'                  => WeeklyScheduleResource::make(
                $this->relationLoaded('workDays') ? $this->workDays : null
            ),
        ];
    }
}
