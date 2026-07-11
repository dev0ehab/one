<?php

namespace App\Http\Requests\Admin\sliders;

use App\Http\Requests\BaseRequest;

class ChangeActivation extends BaseRequest
{
    public function rules()
    {
        return [
            'is_active' => 'required|in:1,0',
        ];
    }
}
