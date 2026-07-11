<?php

namespace App\Http\Requests\Api\Client\Wallet;

use App\Http\Requests\BaseRequest;
use App\Models\Client;

class TransferRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1',
            'phone'        => 'required|exists:clients,phone',
            'country_code' => 'required|exists:countries,code',
        ];
    }

    public function withValidator($validator): void
    {
        if ($validator->errors()->count() > 0) {
            return;
        }

        $validator->after(function ($validator) {
            $client = Client::where('phone', $this->phone)->where('country_code', $this->country_code)->first();

            if (!$client) {
                return $validator->errors()->add('phone', trans('validation.exists', ['attribute' => __('validation.attributes.client')]));
            }

            if ($client->is(auth()->user())) {
                return $validator->errors()->add('error', trans('validation.cant_transfer_to_yourself'));
            }

            if ($this->amount > auth()->user()->wallet->balance) {
                return $validator->errors()->add('amount', trans('validation.insufficient_balance'));
            }

            $this->merge([
                'client' => $client,
            ]);
        });
    }

    public function prepareForValidation()
    {
        $this->merge([
            'phone' => fixPhone($this->phone),
            'country_code' => fixPhone($this->country_code),
        ]);
    }
}
