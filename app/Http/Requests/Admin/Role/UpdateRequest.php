<?php

namespace App\Http\Requests\Admin\Role;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name.ar'     => 'required|max:50',
            'name.en'     => 'required|max:50',
            'permissions' => 'nullable|array'
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'permissions' => array_merge(request()->permissions ?? [], Role::minimum_roles_admin_can_have()),
        ]);
    }
}

