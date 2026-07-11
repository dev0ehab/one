<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Admin extends AuthBaseModel
{
    const IMAGEPATH = 'admins';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'image',
        'role_id',
        'is_notify',
        'is_blocked',
        'type',
    ];

    protected $casts = [
        'is_notify' => 'boolean',
        'is_blocked' => 'boolean',
    ];

    /**
     * Get the role that owns the Admin
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class)->withTrashed();
    }

    public function scopeWhereHasRole($query, $role_name)
    {
        return $query->whereHas("role", function ($q) use ($role_name) {
            return $q->whereJsonContains("name->en", $role_name);
        });
    }

    public function scopeWhereDoesntHasRole($query, $role_name)
    {
        return $query->whereHas("role", function ($q) use ($role_name) {
            return $q->whereJsonDoesntContain("name->en", $role_name);
        });
    }
}
