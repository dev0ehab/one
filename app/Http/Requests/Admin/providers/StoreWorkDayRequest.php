<?php

namespace App\Http\Requests\Admin\providers;

use App\Http\Requests\BaseRequest;

class StoreWorkDayRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'day'       => 'required|string|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'start_at'  => 'required|date_format:H:i',
            'end_at'    => 'required|date_format:H:i|after:start_at',
            'name_ar'   => 'nullable|string|max:255',
            'name_en'   => 'nullable|string|max:255',
        ];
    }
}
