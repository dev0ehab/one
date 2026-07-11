<?php

namespace App\Models;

use App\Enums\SubscriptionStatusEnum;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProviderSubscription extends BaseModel
{
    use ModelTrait;

    protected $fillable = [
        'provider_id',
        'package_id',
        'paid_amount',
        'payment_method',
        'status',
        'ads_remaining',
        'starts_at',
        'ends_at',
    ];

    protected $casts = [
        'paid_amount'    => 'float',
        'ads_remaining'  => 'integer',
        'starts_at'      => 'datetime',
        'ends_at'        => 'datetime',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class)->withTrashed();
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function ads(): HasMany
    {
        return $this->hasMany(ProviderAd::class);
    }

    public function isActive(): bool
    {
        return $this->status === SubscriptionStatusEnum::ACTIVE
            && $this->ends_at->isFuture();
    }

    public function hasAdsRemaining(): bool
    {
        return $this->isActive() && $this->ads_remaining > 0;
    }
}
