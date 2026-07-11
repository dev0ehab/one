<?php

namespace App\Models;

use App\Notifications\GlobalNotification;
use App\Jobs\SendNotification;
use App\Traits\ModelTrait;
use App\Traits\QrTrait;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Wallet extends BaseModel
{
    use ModelTrait, QrTrait;

    public const IMAGEPATH = 'wallets';

    protected $fillable = [
        'walletable_id',
        'walletable_type',
        'balance',
    ];
    public function walletable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function chargeRequests()
    {
        return $this->hasMany(WalletChargeRequest::class);
    }

    public function withdrawalRequests()
    {
        return $this->hasMany(WalletWithdrawalRequest::class);
    }




    // boot
    public static function boot(): void
    {
        parent::boot();
        static::updated(function ($model) {
            $old_balance = $model->getOriginal('balance');
            $new_balance = $model->balance;
            if ($old_balance >= 0 && $new_balance < 0) {
                $amount = $new_balance * -1;
                SendNotification::dispatch(
                    $model->walletable,
                    $model->walletable->getMorphClass(),
                    $model->walletable->id,
                    GlobalNotification::class,
                    __('notification.title_wallet_debt', [], 'ar'),
                    __('notification.title_wallet_debt', [], 'en'),
                    __('notification.body_wallet_debt', ['amount' => $amount . ' ' . __('site.currency' , [], 'ar')], 'ar'),
                    __('notification.body_wallet_debt', ['amount' => $amount . ' ' . __('site.currency' , [], 'en' )], 'en'),
                    'wallet_debt'
                );
            }
        });
    }
}
