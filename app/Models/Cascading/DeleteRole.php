<?php

namespace App\Models\Cascading;

class DeleteRole
{
    /**
     * @var \App\Models\Role
     */
    protected $role;

    public function __construct($role)
    {
        $this->role = $role;
    }

    public function checkAdmins()
    {
        if ($this->role->admins()->exists()) {
            abort(403, __('admin.exceptions.cannot_delete_role_for_exist_admins'));
        }

        return $this;
    }
}
