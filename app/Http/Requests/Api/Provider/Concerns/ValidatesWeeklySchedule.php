<?php

namespace App\Http\Requests\Api\Provider\Concerns;

use App\Enums\WorkDayEnum;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

trait ValidatesWeeklySchedule
{
    protected function prepareWeeklyScheduleForValidation(): void
    {
        $schedule = $this->input('weekly_schedule');

        if (!is_array($schedule)) {
            return;
        }

        foreach ($schedule as $i => $dayBlock) {
            if (!is_array($dayBlock) || empty($dayBlock['periods']) || !is_array($dayBlock['periods'])) {
                continue;
            }

            if (filter_var($dayBlock['closed'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            foreach ($dayBlock['periods'] as $j => $period) {
                if (!is_array($period)) {
                    continue;
                }

                foreach (['start', 'end'] as $key) {
                    if (!isset($period[$key]) || !is_string($period[$key])) {
                        continue;
                    }

                    $normalized = $this->normalizeWeeklyScheduleTime($period[$key]);
                    if ($normalized !== null) {
                        $schedule[$i]['periods'][$j][$key] = $normalized;
                    }
                }
            }
        }

        $this->merge(['weekly_schedule' => $schedule]);
    }

    protected function weeklyScheduleRules(bool $required = true): array
    {
        $weekdayValues = array_values(WorkDayEnum::toArray());
        $size = count($weekdayValues);

        $scheduleRules = $required
            ? ['required', 'array', 'size:' . $size]
            : ['nullable', 'array', 'size:' . $size];

        return [
            'weekly_schedule'             => $scheduleRules,
            'weekly_schedule.*.day'       => ['required_with:weekly_schedule', 'string', Rule::in($weekdayValues)],
            'weekly_schedule.*.closed'    => ['required_with:weekly_schedule', 'boolean'],
            'weekly_schedule.*.periods'   => ['nullable', 'array'],
        ];
    }

    protected function validateWeeklySchedule(Validator $validator): void
    {
        $this->validateWeeklyScheduleWeekCoverage($validator);
        $this->validateWeeklyScheduleDayBlocks($validator);
    }

    protected function buildWorkDaysForPersistence(): array
    {
        $rows = [];

        foreach ($this->input('weekly_schedule', []) as $block) {
            if (filter_var($block['closed'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
                continue;
            }

            foreach ($block['periods'] ?? [] as $period) {
                if (!is_array($period)) {
                    continue;
                }

                $rows[] = [
                    'day'      => $block['day'],
                    'start_at' => $period['start'],
                    'end_at'   => $period['end'],
                    'name'     => WorkDayEnum::defaultNames($block['day']),
                ];
            }
        }

        return $rows;
    }

    private function validateWeeklyScheduleWeekCoverage(Validator $validator): void
    {
        $blocks = $this->input('weekly_schedule', []);
        $expected = array_values(WorkDayEnum::toArray());
        $received = collect($blocks)->pluck('day')->filter()->all();

        if (count(array_unique($received)) !== count($expected)) {
            $validator->errors()->add(
                'weekly_schedule',
                __('validation.provider_work_days_duplicate_or_missing_days')
            );
        }
    }

    private function validateWeeklyScheduleDayBlocks(Validator $validator): void
    {
        foreach ($this->input('weekly_schedule', []) as $i => $block) {
            $closed = filter_var($block['closed'] ?? false, FILTER_VALIDATE_BOOLEAN);
            $periods = $block['periods'] ?? [];

            if ($closed) {
                if (is_array($periods) && count($periods) > 0) {
                    $validator->errors()->add(
                        "weekly_schedule.$i.periods",
                        __('validation.provider_work_days_closed_with_periods')
                    );
                }
                continue;
            }

            if (!is_array($periods) || count($periods) < 1) {
                $validator->errors()->add(
                    "weekly_schedule.$i.periods",
                    __('validation.provider_work_days_open_needs_periods')
                );
                continue;
            }

            foreach ($periods as $j => $period) {
                if (!is_array($period)) {
                    $validator->errors()->add(
                        "weekly_schedule.$i.periods.$j",
                        __('validation.array', ['attribute' => __('validation.attributes.weekly_schedule_period')])
                    );
                    continue;
                }

                $start = $period['start'] ?? null;
                $end = $period['end'] ?? null;

                foreach (['start' => $start, 'end' => $end] as $field => $value) {
                    if ($value === null || $value === '') {
                        $attrKey = $field === 'start' ? 'weekly_schedule_period_start' : 'weekly_schedule_period_end';
                        $validator->errors()->add(
                            "weekly_schedule.$i.periods.$j.$field",
                            __('validation.required', [
                                'attribute' => __('validation.attributes.' . $attrKey),
                            ])
                        );
                    }
                }

                if ($validator->errors()->has("weekly_schedule.$i.periods.$j.start")
                    || $validator->errors()->has("weekly_schedule.$i.periods.$j.end")) {
                    continue;
                }

                $startAt = $this->parseWeeklyScheduleTime((string) $start);
                $endAt = $this->parseWeeklyScheduleTime((string) $end);

                if ($startAt === null) {
                    $validator->errors()->add(
                        "weekly_schedule.$i.periods.$j.start",
                        __('validation.date_format', [
                            'attribute' => __('validation.attributes.weekly_schedule_period_start'),
                            'format'    => 'H:i',
                        ])
                    );
                }

                if ($endAt === null) {
                    $validator->errors()->add(
                        "weekly_schedule.$i.periods.$j.end",
                        __('validation.date_format', [
                            'attribute' => __('validation.attributes.weekly_schedule_period_end'),
                            'format'    => 'H:i',
                        ])
                    );
                }

                if ($startAt === null || $endAt === null) {
                    continue;
                }

                if ($endAt->lessThanOrEqualTo($startAt)) {
                    $validator->errors()->add(
                        "weekly_schedule.$i.periods.$j.end",
                        __('validation.after', [
                            'attribute' => __('validation.attributes.weekly_schedule_period_end'),
                            'date'      => __('validation.attributes.weekly_schedule_period_start'),
                        ])
                    );
                }
            }
        }
    }

    private function normalizeWeeklyScheduleTime(string $value): ?string
    {
        $parsed = $this->parseWeeklyScheduleTime($value);

        return $parsed?->format('H:i');
    }

    private function parseWeeklyScheduleTime(string $value): ?Carbon
    {
        $value = trim($value);

        foreach (['H:i', 'H:i:s'] as $format) {
            try {
                return Carbon::createFromFormat($format, $value);
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
    }
}
