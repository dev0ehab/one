<?php

namespace App\Http\Requests\Admin\introReviews;

use App\Http\Requests\BaseRequest;

class Update extends BaseRequest
{
    public function rules()
    {
        return [
            'image'         => 'nullable|image|mimes:jpg,jpeg,png',
            'comment'       => 'required|array',
            'comment.ar'   => 'required|string|max:1000',
            'comment.en'   => 'required|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'image.image' => __('validation.intro_reviews.image.image'),
            'image.mimes' => __('validation.intro_reviews.image.mimes'),
            'comment.required' => __('validation.intro_reviews.comment.required'),
            'comment.array' => __('validation.intro_reviews.comment.array'),
            'comment.ar.required' => __('validation.intro_reviews.comment.ar.required'),
            'comment.ar.string' => __('validation.intro_reviews.comment.ar.string'),
            'comment.ar.max' => __('validation.intro_reviews.comment.ar.max'),
            'comment.en.required' => __('validation.intro_reviews.comment.en.required'),
            'comment.en.string' => __('validation.intro_reviews.comment.en.string'),
            'comment.en.max' => __('validation.intro_reviews.comment.en.max'),
        ];
    }
}
