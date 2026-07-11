<?php

namespace App\Http\Requests\Api\Provider\Profile;

use App\Http\Requests\BaseRequest;

class UpdateLangRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'device_id'        => 'required|max:250',
            'device_type'      => 'required|in:ios,android,web',
            'mac_address'      => 'required|max:250',
            'preferred_locale' => 'required|in:ar,en',
        ];
    }
}
