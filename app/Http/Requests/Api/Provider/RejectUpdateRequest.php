<?php

namespace App\Http\Requests\Api\Provider;

use App\Http\Requests\BaseRequest;

class RejectUpdateRequest extends BaseRequest
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
            'rejection_reason.required' => __('validation.required', ['attribute' => __('validation.attributes.rejection_reason')]),
            'rejection_reason.string' => __('validation.string', ['attribute' => __('validation.attributes.rejection_reason')]),
            'rejection_reason.max' => __('validation.max.string', ['attribute' => __('validation.attributes.rejection_reason'), 'max' => 500]),
            'rejection_reason.min' => __('validation.min.string', ['attribute' => __('validation.attributes.rejection_reason'), 'min' => 10]),
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'rejection_reason' => __('validation.attributes.rejection_reason'),
        ];
    }
}
