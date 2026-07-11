<?php

namespace App\Models;

use App\Models\Helpers\ClientHelpers;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends AuthBaseModel
{
    use ModelTrait, SoftDeletes, ClientHelpers;

    public const IMAGEPATH = 'clients';

    protected $fillable = [
        'name',
        'image',
        'country_code',
        'phone',
        'password',
        'is_active',
        'is_blocked',
        'is_notify',
        'points',
        'delete_reason',
        'bookings_empty_banner_dismissed',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_blocked' => 'boolean',
        'is_notify'  => 'boolean',
        'bookings_empty_banner_dismissed' => 'boolean',
    ];

    public function locations(): HasMany
    {
        return $this->hasMany(Location::class);
    }

    public function loyaltyPoints(): HasMany
    {
        return $this->hasMany(Point::class);
    }

    public function getLoyaltyBalanceAttribute(): float
    {
        return (float) ($this->attributes['points'] ?? 0);
    }

    public function mainLocation(): HasOne
    {
        return $this->hasOne(Location::class)->where('is_main_location', true);
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    public function carts(): HasMany
    {
        return $this->hasMany(Cart::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function wallet(): MorphOne
    {
        return $this->morphOne(Wallet::class, 'walletable');
    }


    public static function boot(): void
    {
        parent::boot();

        self::creating(function ($client) {});

        self::created(function ($client) {
            $client->wallet()->create();
        });
    }
}
