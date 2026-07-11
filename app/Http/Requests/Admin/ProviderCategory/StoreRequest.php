<?php

namespace App\Http\Requests\Admin\ProviderCategory;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected function prepareForValidation()
    {
        $this->merge([
            //
        ]);
    }

    public function rules()
    {
        return [
            'name'     => 'required|array',
            'name.ar'  => 'required|string|max:50',
            'name.en'  => 'required|string|max:50',
            'image'    => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => __('admin.category_name_is_required'),
            'name.*.required' => __('admin.category_name_is_required'),
            'name.*.string' => __('admin.category_name_must_be_string'),
            'name.*.max' => __('admin.category_name_max_50'),
            'image.image' => __('admin.image_must_be_image'),
            'image.mimes' => __('admin.image_must_be_jpeg_png_jpg_gif'),
            'image.max' => __('admin.image_max_2mb'),
        ];
    }
}

