<?php

namespace App\Models;

use App\Enums\WalletTransactionType;
use App\Notifications\GlobalNotification;
use App\Jobs\SendNotification;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WalletTransaction extends BaseModel
{
    use ModelTrait;

    protected $fillable = [
        'wallet_id',
        'amount',
        'operation',
        'type',
        'clientable_id',
        'clientable_type',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function clientable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }


    public static function boot(): void
    {
        parent::boot();
        static::creating(function ($model) {
            $model->clientable_type = $model->clientable_type ?? (auth()->check() ? get_class(auth()->user()) : Client::class);
            $model->clientable_id = $model->clientable_id ?? (auth()->check() ? auth()->id() : 1);
        });

    }
}
