<?php

namespace App\Http\Requests\Api\Provider\Profile;

use App\Http\Requests\Api\Provider\Concerns\ValidatesWeeklySchedule;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Validator;

class UpdateWorkDaysRequest extends BaseRequest
{
    use ValidatesWeeklySchedule;

    protected function prepareForValidation(): void
    {
        $this->prepareWeeklyScheduleForValidation();
    }

    public function rules(): array
    {
        return $this->weeklyScheduleRules(required: true);
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $this->validateWeeklySchedule($validator);
        });
    }

    public function validated($key = null, $default = null)
    {
        if ($key !== null) {
            return parent::validated($key, $default);
        }

        return array_merge(parent::validated(), [
            'work_days' => $this->buildWorkDaysForPersistence(),
        ]);
    }
}
