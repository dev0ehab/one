<?php

namespace App\Http\Requests\Api\Client\Wallet;

use App\Http\Requests\BaseRequest;

class ChargeWalletRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
        ];
    }
}

