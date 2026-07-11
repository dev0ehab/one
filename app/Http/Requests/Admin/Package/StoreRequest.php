<?php

namespace App\Http\Requests\Admin\Package;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'           => 'required|array',
            'name.ar'        => 'required|string|max:191',
            'name.en'        => 'required|string|max:191',
            'description'    => 'nullable|array',
            'description.ar' => 'nullable|string|max:1000',
            'description.en' => 'nullable|string|max:1000',
            'price'          => 'required|numeric|min:0',
            'ads_count'      => 'required|integer|min:1|max:999',
            'duration_days'  => 'required|integer|min:1|max:3650',
            'sort'           => 'nullable|integer|min:0|max:9999',
            'is_active'      => 'nullable|boolean',
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
