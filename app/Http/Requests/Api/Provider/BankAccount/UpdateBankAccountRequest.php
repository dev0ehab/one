<?php

namespace App\Http\Requests\Api\Provider\BankAccount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBankAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $providerId = auth()->id();

        return [
            'bank_name'    => ['required', 'string', 'max:50'],
            'account_name' => ['required', 'string', 'max:50'],
            'account_num'  => ['required', 'string', 'max:50'],
            'iban'         => [
                'required',
                'string',
                'max:24',
                'min:24',
                'regex:/^[A-Z]{2}[0-9]{22}$/',
                'unique:providers,iban,' . $providerId . ',id,deleted_at,NULL',
            ],
        ];
    }
}
