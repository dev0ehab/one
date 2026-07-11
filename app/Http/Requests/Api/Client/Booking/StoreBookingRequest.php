<?php

namespace App\Http\Requests\Api\Client\Booking;

use App\Http\Requests\BaseRequest;
use App\Models\Provider;
use App\Models\ProviderAd;
use App\Services\Booking\BookingAvailabilityService;
use Carbon\Carbon;

class StoreBookingRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'ad_id' => 'required|exists:provider_ads,id,deleted_at,NULL',
            'date'  => 'required|date_format:Y-m-d|after_or_equal:today',
            'time'  => ['required', 'regex:/^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/'],
            'notes' => 'nullable|string|max:2000',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('time')) {
            $normalized = $this->normalizeTime((string) $this->time);
            if ($normalized !== null) {
                $this->merge(['time' => $normalized]);
            }
        }
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $ad = ProviderAd::query()
                ->active()
                ->with('provider')
                ->find($this->ad_id);

            if (!$ad) {
                $validator->errors()->add('ad_id', __('apis.ad_not_available'));

                return;
            }

            $provider = $ad->provider;

            if (!$provider
                || !$provider->is_active
                || $provider->is_blocked
                || (int) $provider->is_approved !== 1) {
                $validator->errors()->add('ad_id', __('apis.provider_not_available'));

                return;
            }

            if (!$provider->hasActiveBookingService()) {
                $validator->errors()->add('ad_id', __('apis.provider_booking_not_available'));

                return;
            }

            try {
                $date = Carbon::createFromFormat('Y-m-d', $this->date);
            } catch (\Throwable) {
                return;
            }

            if ($date->isToday() && $this->filled('time')) {
                $slotAt = Carbon::parse($this->date . ' ' . $this->time);
                if ($slotAt->lte(now())) {
                    $validator->errors()->add('time', __('apis.booking_slot_in_past'));
                }
            }

            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            try {
                app(BookingAvailabilityService::class)->assertSlotAvailable(
                    $provider,
                    $this->date,
                    $this->time
                );
            } catch (\Illuminate\Validation\ValidationException $e) {
                foreach ($e->errors() as $field => $messages) {
                    foreach ($messages as $message) {
                        $validator->errors()->add($field, $message);
                    }
                }
            }
        });
    }

    public function provider(): Provider
    {
        return ProviderAd::query()
            ->active()
            ->with('provider')
            ->findOrFail($this->ad_id)
            ->provider;
    }

    public function providerAd(): ProviderAd
    {
        return ProviderAd::query()->active()->findOrFail($this->ad_id);
    }

    public function scheduledAt(): Carbon
    {
        return app(BookingAvailabilityService::class)->buildScheduledAt(
            $this->date,
            $this->time
        );
    }

    private function normalizeTime(string $time): ?string
    {
        foreach (['H:i', 'H:i:s'] as $format) {
            try {
                return Carbon::createFromFormat($format, trim($time))->format('H:i');
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }
}
