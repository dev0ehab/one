<?php

namespace App\Http\Requests\Admin\clients;

use App\Http\Requests\BaseRequest;

class UpdateBalance extends BaseRequest
{
    public function rules()
    {
        return [
            'balance' => 'required|numeric|min:1|max:9999999',
            'type'    => 'required|in:0,1',
        ];
    }

    
}

