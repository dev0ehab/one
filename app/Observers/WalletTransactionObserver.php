<?php

namespace App\Observers;

use App\Enums\WalletTransactionType;
use App\Jobs\SendNotification;
use App\Models\WalletTransaction;
use App\Notifications\GlobalNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WalletTransactionObserver implements ShouldQueue
{
    use InteractsWithQueue;

    public bool $afterCommit = true;
    /**
     * Handle the WalletTransaction "created" event.
     */
    public function created(WalletTransaction $transaction): void
    {
        $type = $this->resolveTypeKey($transaction);

        if (!$type) {
            return;
        }

        $amount = $transaction->amount;
        $walletable = $transaction->wallet->walletable;
        $walletMorph = $transaction->wallet->getMorphClass();
        $clientName = $type === 'wallet_transfer_withdrawal'
            ? $transaction->clientable->name
            : $walletable->name;

        SendNotification::dispatch(
            $walletable,
            $walletMorph,
            $transaction->wallet->id,
            GlobalNotification::class,
            __('notification.title_' . $type, [], 'ar'),
            __('notification.title_' . $type, [], 'en'),
            __('notification.body_' . $type, [
                'amount' => $amount . ' ' . __('site.currency', [], 'ar'),
                'name' => $clientName,
            ], 'ar'),
            __('notification.body_' . $type, [
                'amount' => $amount . ' ' . __('site.currency', [], 'en'),
                'name' => $clientName,
            ], 'en'),
            $type
        );
    }

    /**
     * Resolve notification type key based on transaction type/operation.
     */
    protected function resolveTypeKey(WalletTransaction $transaction): ?string
    {
        // Skip notification for WITHDRAW type - handled by WithdrawalRequest model
        if ($transaction->type === WalletTransactionType::WITHDRAW) {
            return null;
        }

        return match ($transaction->type) {
            WalletTransactionType::CHARGE   => 'wallet_charge',
            WalletTransactionType::BOOKING    => 'wallet_booking',
            WalletTransactionType::TRANSFER   => 'wallet_transfer_' . $transaction->operation,
            WalletTransactionType::CASHBACK   => 'wallet_cashback',
            default => null,
        };
    }
}

