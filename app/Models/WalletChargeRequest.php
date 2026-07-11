<?php

namespace App\Models;

use App\Jobs\SendNotification;
use App\Notifications\GlobalNotification;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletChargeRequest extends BaseModel
{
    use ModelTrait;

    public const IMAGEPATH = 'wallet_charge_requests';


    protected $fillable = [
        'wallet_id',
        'amount',
        'status',
        'wallet_transaction_id',
        'image',
        'reason'
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function walletTransaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class);
    }

    // boot
    public static function boot(): void
    {
        parent::boot();
        static::updated(function ($model) {
            if ($model->isDirty('status') && $model->status == 'rejected') {
                SendNotification::dispatch(
                    $model->wallet->walletable,
                    $model->wallet->walletable->getMorphClass(),
                    $model->wallet->walletable->id,
                    GlobalNotification::class,
                    __('notification.title_wallet_charge_request_rejected', ['reason' => $model->reason], 'ar'),
                    __('notification.title_wallet_charge_request_rejected', ['reason' => $model->reason], 'en'),
                    __('notification.body_wallet_charge_request_rejected', ['reason' => $model->reason], 'ar'),
                    __('notification.body_wallet_charge_request_rejected', ['reason' => $model->reason], 'en'),
                    'wallet_charge_request_rejected',
                );
            }
        });
    }
}
