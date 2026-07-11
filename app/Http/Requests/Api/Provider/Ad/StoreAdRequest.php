<?php

namespace App\Http\Requests\Api\Provider\Ad;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'start_at'      => ['required', 'date', 'after_or_equal:today'],
            'price'         => ['required', 'numeric', 'min:0'],
            'images'        => ['nullable', 'array', 'max:5'],
            'images.*'      => ['required', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:5120'],
        ];
    }
}
