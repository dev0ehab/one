<?php

namespace App\Http\Requests\Admin\providers;

use App\Enums\WorkDayEnum;
use App\Http\Requests\BaseRequest;
use Carbon\Carbon;

class Update extends BaseRequest
{
    public function rules()
    {
        $providerId = $this->route('id');

        return [
            'owner_name'              => 'required|max:50',
            'trade_name'              => 'required|max:50',
            'country_code'            => 'required|numeric|digits_between:2,5',
            'phone'                   => 'required|numeric|digits_between:9,10|unique:providers,phone,' . $providerId . ',id,deleted_at,NULL|starts_with:5',
            'email'                   => 'required|email|unique:providers,email,' . $providerId . ',id,deleted_at,NULL',
            'national_id'             => 'required|numeric|digits:10',
            'commercial_register_num' => 'required|numeric|digits_between:10,16',
            'category_id'             => 'required|exists:categories,id',
            'lat'                     => 'required|numeric|between:-90,90',
            'lng'                     => 'required|numeric|between:-180,180',
            'map_desc'                => 'required|max:255',
            'city_id'                 => 'nullable|exists:cities,id',
            'work_days'               => 'nullable|array',
            'work_days.*.day'         => 'required|in:' . implode(',', WorkDayEnum::toArray()),
            'work_days.*.name.ar'     => 'required|string|max:50',
            'work_days.*.name.en'     => 'required|string|max:50',
            'work_days.*.start_at'    => 'required|date_format:H:i',
            'work_days.*.end_at'      => 'required|date_format:H:i|after:work_days.*.start_at',
            'bank_name'               => 'required|max:50',
            'account_name'            => 'required|max:50',
            'account_num'             => 'required|max:50',
            'iban'                    => 'required|max:24|min:24|regex:/^[A-Z]{2}[0-9]{22}$/',
            'image'                   => 'nullable|image|mimes:jpg,jpeg,png',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $workDays = $this->input('work_days', []);

            if (!is_array($workDays) || empty($workDays)) {
                return;
            }

            foreach ($workDays as $index => $workDay) {
                if (!is_array($workDay) || !isset($workDay['start_at'], $workDay['end_at'])) {
                    continue;
                }

                try {
                    $startAt = Carbon::createFromFormat('H:i', $workDay['start_at']);
                    $endAt = Carbon::createFromFormat('H:i', $workDay['end_at']);

                    if ($endAt->lte($startAt)) {
                        $validator->errors()->add(
                            "work_days.{$index}.end_at",
                            __('validation.after', ['attribute' => __('admin.end_time'), 'date' => __('admin.start_time')])
                        );
                    }
                } catch (\Exception $e) {
                }
            }
        });
    }
}
