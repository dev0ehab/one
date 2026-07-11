<?php

namespace App\Http\Requests\Api\Client\Inquiry;

use App\Enums\InquiryTargetTypeEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class StoreInquiryRequest extends BaseRequest
{
    public function rules(): array
    {
        return [
            'category_id'  => 'required|exists:categories,id',
            'title'        => 'required|string|min:3|max:191',
            'description'  => 'required|string|min:10|max:2000',
            'target_type'  => ['required', Rule::in(InquiryTargetTypeEnum::toArray())],
            'lat'          => 'required_if:target_type,nearest|nullable|numeric|between:-90,90',
            'lng'          => 'required_if:target_type,nearest|nullable|numeric|between:-180,180',
            'address'      => 'nullable|string|max:500',
            'images'       => 'nullable|array|max:5',
            'images.*'     => 'image|mimes:jpg,jpeg,png|max:10240',
        ];
    }
}
