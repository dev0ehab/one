<?php

namespace App\Http\Requests\Admin\Country;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            //'code' => '966',
        ]);
    }

    public function rules()
    {
        $countryId = $this->route('id');
        
        return [
            'name'    => 'required|array',
            'name.ar' => 'required|string|max:50',
            'name.en' => 'required|string|max:50',
            'code'    => 'required|string|numeric|unique:countries,code,' . $countryId,
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('admin.country_name_is_required'),
            'name.*.required' => __('admin.country_name_is_required'),
            'name.*.string' => __('admin.country_name_must_be_string'),
            'name.*.max' => __('admin.country_name_max_50'),
            'code.required' => __('admin.country_code_is_required'),
            'code.string' => __('admin.country_code_must_be_string'),
            'code.max' => __('admin.country_code_max_10'),
            'code.unique' => __('admin.country_code_already_exists'),
            'image.image' => __('admin.image_must_be_image'),
            'image.mimes' => __('admin.image_must_be_jpeg_png_jpg_gif'),
            'image.max' => __('admin.image_max_2mb'),
        ];
    }
}
