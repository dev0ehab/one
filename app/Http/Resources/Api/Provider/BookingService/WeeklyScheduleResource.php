<?php

namespace App\Http\Resources\Api\Provider\BookingService;

use App\Enums\WorkDayEnum;
use App\Models\WorkDay;
use Illuminate\Support\Collection;

class WeeklyScheduleResource
{
    /**
     * Group provider work days by weekday, returning every weekday with its
     * periods (mirrors the weekly_schedule update payload).
     *
     * @param  Collection<int, WorkDay>|null  $workDays
     * @return array<int, array<string, mixed>>
     */
    public static function make(?Collection $workDays): array
    {
        $workDays ??= collect();
        $locale = app()->getLocale();

        $grouped = $workDays->groupBy('day');

        $schedule = [];

        foreach (array_values(WorkDayEnum::toArray()) as $day) {
            $periods = ($grouped->get($day) ?? collect())
                ->filter(fn (WorkDay $wd) => $wd->start_at !== null && $wd->end_at !== null)
                ->sortBy(fn (WorkDay $wd) => $wd->start_at->format('H:i'))
                ->map(fn (WorkDay $wd) => [
                    'id'    => $wd->id,
                    'start' => $wd->start_at->format('H:i'),
                    'end'   => $wd->end_at->format('H:i'),
                ])
                ->values()
                ->all();

            $schedule[] = [
                'day'     => $day,
                'name'    => WorkDayEnum::defaultNames($day)[$locale]
                    ?? WorkDayEnum::defaultNames($day)['ar']
                    ?? $day,
                'closed'  => $periods === [],
                'periods' => $periods,
            ];
        }

        return $schedule;
    }
}
