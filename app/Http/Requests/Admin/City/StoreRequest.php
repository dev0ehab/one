<?php

namespace App\Http\Requests\Admin\City;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name'        => 'required|array',
            'name.ar'     => 'required|string|max:50',
            'name.en'     => 'required|string|max:50',
            'country_id'  => 'required|exists:countries,id',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => __('admin.city_name_is_required'),
            'name.*.required' => __('admin.city_name_is_required'),
            'name.*.string' => __('admin.city_name_must_be_string'),
            'name.*.max' => __('admin.city_name_max_50'),
            'country_id.required' => __('admin.country_is_required'),
            'country_id.exists' => __('admin.country_not_found'),
        ];
    }
}
