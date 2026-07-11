<?php

namespace App\Http\Requests\Admin\roles;

use App\Models\Role;
use Illuminate\Foundation\Http\FormRequest;

class Update extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name.ar'     => 'required|max:191',
            'name.en'     => 'required|max:191',
            'permissions' => 'nullable|array'
        ];
    }


    public function prepareForValidation()
    {
        $this->merge([
            'permissions' => array_merge(request()->permissions , Role::minimum_roles_admin_can_have()),
        ]);
    }
}
