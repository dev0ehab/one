<?php

namespace App\Models;

use App\Models\Cascading\DeleteRole;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseModel
{
    use SoftDeletes, HasTranslations;

    protected $fillable = ['name'];

    public $translatable = ['name'];

    /**
     * Get all of the permissions for the Role
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    public function admins()
    {
        return $this->hasMany(Admin::class);
    }


    public static function minimum_roles_admin_can_have()
    {
        return [
            "admin.dashboard",
            "admin.dashboard-users",
            "admin.profile.profile",
            "admin.profile.update",
            "admin.profile.update_password",
        ]; 
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($role) {
            (new DeleteRole($role))
                ->checkAdmins();
        });
    }
}
