<?php

namespace App\Http\Requests\Admin\Complain;

use Illuminate\Foundation\Http\FormRequest;

class CompletedRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'reply' => 'required|string|min:2|max:500',
        ];
    }

    public function messages()
    {
        return [
            'reply.required'  => __('admin.reply_is_required_when_status_is_finish'),
            'reply.min'       => __('admin.reply_min_2_characters'),
            'reply.max'       => __('admin.reply_max_500_characters'),
        ];
    }
}


