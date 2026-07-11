<?php

namespace App\Models;

use App\Enums\AdStatusEnum;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProviderAd extends BaseModel
{
    use ModelTrait, SoftDeletes;

    public const IMAGEPATH = 'provider_ads';

    protected $fillable = [
        'provider_id',
        'provider_subscription_id',
        'title',
        'description',
        'start_at',
        'price',
        'status',
        'reject_reason',
        'approved_at',
        'expires_at',
        'views',
        'clicks',
    ];

    protected $casts = [
        'start_at'    => 'datetime',
        'price'       => 'float',
        'approved_at' => 'datetime',
        'expires_at'  => 'datetime',
    ];

    public function provider(): BelongsTo
    {
        return $this->belongsTo(Provider::class)->withTrashed();
    }

    public function subscription(): BelongsTo
    {
        return $this->belongsTo(ProviderSubscription::class, 'provider_subscription_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProviderAdImage::class);
    }

    public function isApproved(): bool
    {
        return $this->status === AdStatusEnum::APPROVED;
    }

    public function scopeApproved($query)
    {
        return $query->where('status', AdStatusEnum::APPROVED);
    }

    public function scopeActive($query)
    {
        return $query->approved()
            ->where(function ($q) {
                $q->whereNull('start_at')
                  ->orWhere('start_at', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }
}
