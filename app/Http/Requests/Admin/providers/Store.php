<?php

namespace App\Http\Requests\Admin\providers;

use App\Enums\WorkDayEnum;
use App\Http\Requests\BaseRequest;

class Store extends BaseRequest
{
    protected function prepareForValidation()
    {
        $this->merge([
            'is_approved'  => 1,
            'phone'        => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }

    public function rules()
    {
        return [
            'owner_name'              => 'required|max:50',
            'trade_name'              => 'required|max:50',
            'country_code'            => 'required|numeric|digits_between:2,5',
            'phone'                   => 'required|numeric|digits_between:9,10|unique:providers,phone,null,id,deleted_at,NULL|starts_with:5',
            'email'                   => 'required|email|unique:providers,email,null,id,deleted_at,NULL',
            'password'                => 'required|string|min:8',
            'national_id'             => 'required|numeric|digits:10',
            'commercial_register_num' => 'required|numeric|digits_between:10,16',
            'category_id'             => 'required|exists:categories,id',
            'lat'                     => 'required|numeric|between:-90,90',
            'lng'                     => 'required|numeric|between:-180,180',
            'map_desc'                => 'required|max:191',
            'city_id'                 => 'nullable|exists:cities,id',
            'work_days'               => 'required|array',
            'work_days.*.day'         => 'required|in:' . implode(',', WorkDayEnum::toArray()),
            'work_days.*.name.ar'     => 'nullable|string|max:191',
            'work_days.*.name.en'     => 'nullable|string|max:191',
            'work_days.*.start_at'    => 'required|date_format:H:i',
            'work_days.*.end_at'      => 'required|date_format:H:i|after:work_days.*.start_at',
            'bank_name'               => 'required|max:50',
            'account_name'            => 'required|max:50',
            'account_num'             => 'required|max:50',
            'iban'                    => 'required|max:24|min:24|regex:/^[A-Z]{2}[0-9]{22}$/',
            'image'                   => 'required|image|mimes:jpg,jpeg,png',
            'is_approved'             => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'work_days.*.end_at.after' => __('admin.end_time_must_be_after_start_time'),
        ];
    }
}
