<?php

namespace App\Http\Requests\Wallet;

use Illuminate\Foundation\Http\FormRequest;

class ChargeWalletRequest extends FormRequest
{
	public function authorize(): bool
	{
		return true; 
	}


	public function rules(): array
	{
		return [
			'amount'  => ['required', 'numeric', 'min:1', 'max:999999999999.99'],
			'type'    => ['required'],
		];
	}


	public function prepareForValidation()
	{
		$this->merge([
			'type'    => 'online',
		]);	
	}

}
