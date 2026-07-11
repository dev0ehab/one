<?php

namespace App\Http\Requests\Api\Client\Wallet;

use App\Http\Requests\BaseRequest;

class ChargeRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'type' => 'required|in:online,transfer',
            'amount' => 'required_if:type,online|numeric',
            'image' => 'required_if:type,transfer|image|mimes:jpg,jpeg,png',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->type == 'transfer' && auth()->user()->wallet->chargeRequests()->where('status', 'pending')->count() > 0) {
                $validator->errors()->add('amount', trans('validation.charge_request_pending'));
            }
        });
    }
} 