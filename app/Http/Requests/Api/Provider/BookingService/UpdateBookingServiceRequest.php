<?php

namespace App\Http\Requests\Api\Provider\BookingService;

use App\Http\Requests\Api\Provider\Concerns\ValidatesWeeklySchedule;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Validator;

class UpdateBookingServiceRequest extends BaseRequest
{
    use ValidatesWeeklySchedule;

    protected function prepareForValidation(): void
    {
        if ($this->exists('show_in_offers')) {
            $this->merge([
                'show_in_offers' => filter_var($this->input('show_in_offers'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }

        $this->prepareWeeklyScheduleForValidation();
    }

    public function rules(): array
    {
        return array_merge([
            'service_duration_minutes' => ['nullable', 'integer', 'min:5', 'max:480'],
            'booking_notes'            => ['nullable', 'string', 'max:2000'],
            'booking_reminder_minutes' => ['nullable', 'integer', 'min:5', 'max:10080'],
            'show_in_offers'           => ['nullable', 'boolean'],
            'discount_percentage'      => ['nullable', 'numeric', 'min:5', 'max:100'],
            'cancellation_policy'      => ['nullable', 'string', 'max:2000'],
        ], $this->weeklyScheduleRules(required: false));
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $hasSettings = $this->exists('service_duration_minutes')
                || $this->exists('booking_notes')
                || $this->exists('booking_reminder_minutes')
                || $this->exists('show_in_offers')
                || $this->exists('discount_percentage')
                || $this->exists('cancellation_policy');
            $hasSchedule = $this->filled('weekly_schedule');

            if (!$hasSettings && !$hasSchedule) {
                $validator->errors()->add(
                    'service_duration_minutes',
                    __('validation.booking_service_update_requires_fields')
                );
            }

            if ($hasSchedule) {
                $this->validateWeeklySchedule($validator);
            }
        });
    }

    public function validated($key = null, $default = null)
    {
        if ($key !== null) {
            return parent::validated($key, $default);
        }

        $data = parent::validated();

        if ($this->filled('weekly_schedule')) {
            $data['work_days'] = $this->buildWorkDaysForPersistence();
        }

        return $data;
    }
}
