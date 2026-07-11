<?php

namespace App\Services\Booking;

use App\Enums\BookingStatusEnum;
use App\Enums\WorkDayEnum;
use App\Models\Booking;
use App\Models\Provider;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

class BookingAvailabilityService
{
    public const DEFAULT_DAYS_AHEAD = 14;

    public function getAvailability(Provider $provider, ?int $daysAhead = null): array
    {
        $daysAhead = max(1, min(60, $daysAhead ?? self::DEFAULT_DAYS_AHEAD));

        if (!$provider->hasActiveBookingService()) {
            return $this->emptyPayload($provider);
        }

        $duration = $this->slotDurationMinutes($provider);
        $provider->loadMissing('workDays');

        $workDaysByDay = $provider->workDays
            ->filter(fn ($wd) => $wd->start_at && $wd->end_at)
            ->groupBy('day');

        $busyIntervals = $this->busyIntervals($provider->id, $duration, $daysAhead);
        $locale = app()->getLocale();
        $dates = [];

        foreach (CarbonPeriod::create(now()->startOfDay(), now()->addDays($daysAhead - 1)->startOfDay()) as $day) {
            $dayKey = strtolower($day->locale('en')->dayName);
            $periods = $workDaysByDay->get($dayKey, collect());

            if ($periods->isEmpty()) {
                continue;
            }

            $slots = [];

            foreach ($periods as $period) {
                $periodStart = Carbon::parse($day->format('Y-m-d') . ' ' . $period->start_at->format('H:i:s'));
                $periodEnd = Carbon::parse($day->format('Y-m-d') . ' ' . $period->end_at->format('H:i:s'));

                for ($cursor = $periodStart->copy(); $cursor->copy()->addMinutes($duration)->lte($periodEnd); $cursor->addMinutes($duration)) {
                    if (!$cursor->isFuture()) {
                        continue;
                    }

                    $slotEnd = $cursor->copy()->addMinutes($duration);

                    if ($this->intervalOverlapsBusy($cursor, $slotEnd, $busyIntervals)) {
                        continue;
                    }

                    $slots[] = [
                        'time'  => $cursor->format('H:i'),
                        'label' => $cursor->locale($locale)->translatedFormat('g:i A'),
                    ];
                }
            }

            if ($slots === []) {
                continue;
            }

            $names = WorkDayEnum::defaultNames($dayKey);

            $dates[] = [
                'date'     => $day->format('Y-m-d'),
                'day'      => $dayKey,
                'day_name' => $names[$locale] ?? $names['en'],
                'slots'    => $slots,
            ];
        }

        return [
            'provider_id'              => $provider->id,
            'service_duration_minutes' => $duration,
            'booking_notes'            => $provider->booking_notes,
            'dates'                    => $dates,
        ];
    }

    public function slotIsAvailable(Provider $provider, string $date, string $time): bool
    {
        if (!$provider->hasActiveBookingService()) {
            return false;
        }

        $normalizedTime = $this->normalizeTime($time);
        if ($normalizedTime === null) {
            return false;
        }

        $availability = $this->getAvailability($provider);

        foreach ($availability['dates'] as $day) {
            if ($day['date'] !== $date) {
                continue;
            }

            foreach ($day['slots'] as $slot) {
                if ($slot['time'] === $normalizedTime) {
                    return true;
                }
            }
        }

        return false;
    }

    public function assertSlotAvailable(Provider $provider, string $date, string $time): void
    {
        if (!$this->slotIsAvailable($provider, $date, $time)) {
            throw ValidationException::withMessages([
                'time' => [__('validation.reservation_time_taken', [
                    'date' => $date,
                    'time' => $time,
                ])],
            ]);
        }
    }

    public function buildScheduledAt(string $date, string $time): Carbon
    {
        $normalizedTime = $this->normalizeTime($time);

        return Carbon::parse($date . ' ' . $normalizedTime);
    }

    protected function slotDurationMinutes(Provider $provider): int
    {
        return max(5, (int) ($provider->service_duration_minutes ?: 30));
    }

    protected function busyIntervals(int $providerId, int $durationMinutes, int $daysAhead): Collection
    {
        $rangeEnd = now()->addDays($daysAhead)->endOfDay();

        return Booking::query()
            ->where('provider_id', $providerId)
            ->where('scheduled_at', '>=', now())
            ->where('scheduled_at', '<=', $rangeEnd)
            ->whereNotIn('status', [BookingStatusEnum::CANCELLED])
            ->get(['scheduled_at'])
            ->map(function (Booking $booking) use ($durationMinutes) {
                $start = $booking->scheduled_at->copy();

                return [
                    'start' => $start,
                    'end'   => $start->copy()->addMinutes($durationMinutes),
                ];
            });
    }

    protected function intervalOverlapsBusy(Carbon $start, Carbon $end, Collection $busyIntervals): bool
    {
        return $busyIntervals->contains(
            fn (array $busy) => $start->lt($busy['end']) && $end->gt($busy['start'])
        );
    }

    protected function normalizeTime(string $time): ?string
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

    protected function emptyPayload(Provider $provider): array
    {
        return [
            'provider_id'              => $provider->id,
            'service_duration_minutes' => null,
            'booking_notes'            => $provider->booking_notes,
            'dates'                    => [],
        ];
    }
}
