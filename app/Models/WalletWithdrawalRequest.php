<?php

namespace App\Models;

use App\Jobs\SendNotification;
use App\Notifications\GlobalNotification;
use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletWithdrawalRequest extends BaseModel
{
    use ModelTrait;

    protected $fillable = [
        'wallet_id',
        'amount',
        'status',
        'wallet_transaction_id',
        'reason',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function walletTransaction(): BelongsTo
    {
        return $this->belongsTo(WalletTransaction::class);
    }

    public static function boot(): void
    {
        parent::boot();

        static::updated(function ($model) {
            if (!$model->isDirty('status') || $model->status === 'pending') {
                return;
            }

            $walletable = $model->wallet?->walletable;

            if (!$walletable || !$walletable->is_notify) {
                return;
            }

            if ($model->status === 'approved') {
                SendNotification::dispatch(
                    $walletable,
                    $walletable->getMorphClass(),
                    $walletable->id,
                    GlobalNotification::class,
                    __('notification.withdraw_accept_title', [], 'ar'),
                    __('notification.withdraw_accept_title', [], 'en'),
                    __('notification.withdraw_accept_body', ['amount' => $model->amount], 'ar'),
                    __('notification.withdraw_accept_body', ['amount' => $model->amount], 'en'),
                    'withdraw_accept',
                );

                return;
            }

            if ($model->status === 'rejected') {
                SendNotification::dispatch(
                    $walletable,
                    $walletable->getMorphClass(),
                    $walletable->id,
                    GlobalNotification::class,
                    __('notification.withdraw_reject_title', [], 'ar'),
                    __('notification.withdraw_reject_title', [], 'en'),
                    __('notification.withdraw_reject_body', ['reason' => $model->reason ?? ''], 'ar'),
                    __('notification.withdraw_reject_body', ['reason' => $model->reason ?? ''], 'en'),
                    'withdraw_reject',
                );
            }
        });
    }
}
