<?php

namespace  App\Services\Entity;

use App\Enums\OTPThrough;
use App\Enums\OTPType;
use App\Enums\PaymentTransactions;
use App\Enums\WalletTransactionType;
use App\Jobs\SendNotification;
use App\Models\Client;
use App\Models\Provider;
use App\Models\SiteSetting;
use App\Models\Wallet;
use App\Models\WalletWithdrawalRequest;
use App\Notifications\GlobalNotification;
use Illuminate\Support\Facades\DB;
use App\Services\PaymentGateway\PaymentService;
use App\Traits\ResponseTrait;
use Symfony\Component\HttpKernel\Exception\HttpException;

class WalletService
{
    use ResponseTrait;

    public function create_wallet($walletable)
    {
        return $walletable->wallet()->create();
    }

    public function deposit($walletable, $amount): void
    {
        try {
            DB::beginTransaction();
            if (!$walletable->wallet()->exists()) {
                $this->create_wallet($walletable);
            }
            $wallet = $walletable->wallet;
            $wallet->transactions()->create([
                'amount' => $amount,
                'operation' => 'deposit',
                'type' => WalletTransactionType::CHARGE,
            ]);
            $walletable->wallet()->increment('balance', $amount);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deduct($walletable, $amount, $transactionType = WalletTransactionType::CHARGE): void
    {
        try {
            DB::beginTransaction();
            if (!$walletable->wallet()->exists()) {
                $this->create_wallet($walletable);
            }
            $wallet = $walletable->wallet;

            if ($wallet->balance < $amount) {
                throw new HttpException(400, __('apis.not_enough_balance'));
            }
            
            $wallet->transactions()->create([
                'amount' => $amount,
                'operation' => 'withdrawal',
                'type' => $transactionType,
            ]);
            $walletable->wallet()->decrement('balance', $amount);
            DB::commit();

            $this->notifyLowBalanceIfNeeded($walletable, $wallet->fresh());
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }



    public function createChargeRequest($walletable, $request)
    {
        try {
            // Ensure wallet exists
            if (!$walletable->wallet()->exists()) {
                $this->create_wallet($walletable);
            }

            $paymentService = new PaymentService();
            $payment        = $paymentService->create()->createPayment($request, $walletable);

            // Create the payment transaction
            $paymentService->createPaymentTransaction(
                $request['amount'],
                PaymentTransactions::CHARGEWALLET,
                Wallet::class,
                $walletable->wallet->id,
                $payment['transaction_id'],
                $payment['paymentResponse'],
                $payment['payment_name']
            );

            return [
                'status'  => 'success',
                'message' => __('apis.wallet_payment_redirect'),
                'data'    => [
                    'redirect_url'   => $payment['redirect_url'],
                    'transaction_id' => $payment['transaction_id'],
                ],
            ];

        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function acceptChargeRequest($chargeRequest): void
    {
        try {
            DB::beginTransaction();
            $wallet = $chargeRequest->wallet;
            $wallet->transactions()->create([
                'amount' => $chargeRequest->amount,
                'operation' => 'deposit',
                'type' => WalletTransactionType::CHARGE,
            ]);
            $wallet->increment('balance', $chargeRequest->amount);
            $chargeRequest->update(['status' => 'approved']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function rejectChargeRequest($chargeRequest, $reason): void
    {
        try {
            DB::beginTransaction();
            $chargeRequest->update([
                'status' => 'rejected',
                'reason' => $reason
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function payOrder($walletable1, $amount1): void
    {
        try {
            DB::beginTransaction();
            $points_to_balance = SiteSetting::where('key', 'points_to_balance')->first()?->value ?? 1;
            $points = $amount1 * $points_to_balance;
            if (!$walletable1->wallet()->exists()) {
                $this->create_wallet($walletable1);
            }
            $walletable1->wallet->transactions()->create([
                'amount' => $amount1,
                'operation' => 'withdrawal',
                'type' => WalletTransactionType::ORDER,
            ]);
            $walletable1->wallet()->decrement('balance', $amount1);
            $walletable1->increment('points', $points);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function transfer($walletable1, $walletable2, $amount): void
    {
        try {
            DB::beginTransaction();
            if (!$walletable1->wallet()->exists()) {
                $this->create_wallet($walletable1);
            }
            if (!$walletable2->wallet()->exists()) {
                $this->create_wallet($walletable2);
            }
            $walletable1->wallet->transactions()->create([
                'amount' => $amount,
                'operation' => 'withdrawal',
                'type' => WalletTransactionType::TRANSFER,
                'clientable_id' => $walletable2->wallet->walletable_id,
                'clientable_type' => get_class($walletable2->wallet->walletable),
            ]);
            $walletable2->wallet->transactions()->create([
                'amount' => $amount,
                'operation' => 'deposit',
                'type' => WalletTransactionType::TRANSFER,
                'clientable_id' => $walletable1->wallet->walletable_id,
                'clientable_type' => get_class($walletable1->wallet->walletable),
            ]);
            $walletable1->wallet()->decrement('balance', $amount);
            $walletable2->wallet()->increment('balance', $amount);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function cacheBack($walletable, $amount): void
    {
        try {
            DB::beginTransaction();
            if (!$walletable->wallet()->exists()) {
                $this->create_wallet($walletable);
            }
            $wallet = $walletable->wallet;
            $wallet->transactions()->create([
                'amount' => $amount,
                'operation' => 'deposit',
                'type' => WalletTransactionType::CASHBACK,
            ]);
            $walletable->wallet()->increment('balance', $amount);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function loyalty($walletable, $points)
    {
        $pointsPerRiyal  = (float) SiteSetting::where('key', 'points_per_riyal')->first()?->value ?? 1;
        $amount          = $points * $pointsPerRiyal;
        $walletable->wallet()->increment('balance', $amount);
        $walletable->loyaltyPoints()->update(['is_used' => 1]);
        return ['status'  => 'success', 'message' => __('apis.success')];
    }

    public function getWallet($walletable): array
    {
        return $this->getWalletForApi($walletable);
    }

    public function getWalletForApi(Client|Provider $walletable): array
    {
        if (!$walletable->wallet()->exists()) {
            $this->create_wallet($walletable);
        }

        $wallet = $walletable->wallet;
        $threshold = (float) (settings()['wallet_low_balance_threshold'] ?? 0);

        $data = [
            'balance'               => round((float) $wallet->balance, 2),
            'currency'              => 'SAR',
            'currency_label'        => (string) __('site.currency'),
            'is_low_balance'        => $threshold > 0 && (float) $wallet->balance < $threshold,
            'low_balance_threshold' => $threshold,
        ];

        if ($walletable instanceof Provider) {
            $data['bank_account_complete'] = $this->providerBankAccountComplete($walletable);
            $data['has_pending_withdrawal'] = $wallet->withdrawalRequests()
                ->where('status', 'pending')
                ->exists();
        }

        return $data;
    }

    public function providerBankAccountComplete(Provider $provider): bool
    {
        return filled($provider->bank_name)
            && filled($provider->account_name)
            && filled($provider->account_num)
            && filled($provider->iban);
    }

    public function getBankAccountForApi(Provider $provider): array
    {
        return [
            'bank_name'    => $provider->bank_name,
            'account_name' => $provider->account_name,
            'account_num'  => $provider->account_num,
            'iban'         => $provider->iban,
            'is_complete'  => $this->providerBankAccountComplete($provider),
        ];
    }

    public function updateBankAccount(Provider $provider, array $data): void
    {
        $provider->update([
            'bank_name'    => $data['bank_name'],
            'account_name' => $data['account_name'],
            'account_num'  => $data['account_num'],
            'iban'         => $data['iban'],
        ]);
    }

    public function createWithdrawalRequest(Provider $provider, float $amount): array
    {
        if (!$this->providerBankAccountComplete($provider)) {
            throw new HttpException(400, __('apis.bank_account_incomplete'));
        }

        if (!$provider->wallet()->exists()) {
            $this->create_wallet($provider);
        }

        $wallet = $provider->wallet;

        if ($wallet->withdrawalRequests()->where('status', 'pending')->exists()) {
            throw new HttpException(400, __('apis.withdrawal_request_pending'));
        }

        if ((float) $wallet->balance < $amount) {
            throw new HttpException(400, __('apis.not_enough_balance'));
        }

        $wallet->withdrawalRequests()->create([
            'amount' => $amount,
            'status' => 'pending',
        ]);

        return ['message' => __('apis.withdrawal_request_submitted')];
    }

    public function approveWithdrawalRequest(WalletWithdrawalRequest $withdrawalRequest): void
    {
        if ($withdrawalRequest->status !== 'pending') {
            throw new HttpException(400, __('apis.invalid_status'));
        }

        $walletable = $withdrawalRequest->wallet->walletable;

        if (!$walletable instanceof Provider) {
            throw new HttpException(400, __('apis.something_went_wrong'));
        }

        DB::transaction(function () use ($withdrawalRequest, $walletable) {
            $this->deduct($walletable, (float) $withdrawalRequest->amount, WalletTransactionType::WITHDRAW);

            $transaction = $withdrawalRequest->wallet->transactions()->latest()->first();

            $withdrawalRequest->update([
                'status'                 => 'approved',
                'wallet_transaction_id' => $transaction?->id,
            ]);
        });
    }

    public function rejectWithdrawalRequest(WalletWithdrawalRequest $withdrawalRequest, ?string $reason = null): void
    {
        if ($withdrawalRequest->status !== 'pending') {
            throw new HttpException(400, __('apis.invalid_status'));
        }

        $withdrawalRequest->update([
            'status' => 'rejected',
            'reason' => $reason,
        ]);
    }

    public function getChargeRequests(Client|Provider $walletable)
    {
        if (!$walletable->wallet()->exists()) {
            $this->create_wallet($walletable);
        }

        return $walletable->wallet->chargeRequests()->latest()->paginate(request('paginate', 15));
    }

    protected function notifyLowBalanceIfNeeded(Client|Provider $walletable, Wallet $wallet): void
    {
        $threshold = (float) (settings()['wallet_low_balance_threshold'] ?? 0);

        if ($threshold <= 0 || (float) $wallet->balance >= $threshold || !$walletable->is_notify) {
            return;
        }

        SendNotification::dispatch(
            $walletable,
            $walletable->getMorphClass(),
            $walletable->id,
            GlobalNotification::class,
            __('notification.title_wallet_low_balance', [], 'ar'),
            __('notification.title_wallet_low_balance', [], 'en'),
            __('notification.body_wallet_low_balance', [
                'balance' => round((float) $wallet->balance, 2) . ' ' . __('site.currency', [], 'ar'),
                'threshold' => $threshold . ' ' . __('site.currency', [], 'ar'),
            ], 'ar'),
            __('notification.body_wallet_low_balance', [
                'balance' => round((float) $wallet->balance, 2) . ' ' . __('site.currency', [], 'en'),
                'threshold' => $threshold . ' ' . __('site.currency', [], 'en'),
            ], 'en'),
            'wallet_low_balance'
        );
    }

    public function getTransactions($walletable)
    {
        if (!$walletable->wallet()->exists()) {
            $this->create_wallet($walletable);
        }
        return $walletable->wallet->transactions()->latest()->paginate(request('paginate', 15));
    }

    public function getTransfers($walletable)
    {
        if (!$walletable->wallet()->exists()) {
            $this->create_wallet($walletable);
        }
        return $walletable->wallet->transactions()->with('clientable')->where('type', WalletTransactionType::TRANSFER)->where('operation', request('operation', 'deposit'))->paginate(request('perPage', 15));
    }

    public function requestTransferCode()
    {
        $user = auth()->user();
        $user->sendVerificationCode(OTPThrough::PHONE, OTPType::TRANSFER_CODE);
        return $this->successMsg(__('auth.phone_code_sent'));
    }

    public function checkTransferCode($code)
    {
        $user = auth()->user();
        $user->otps()->where(['code' => $code, 'type' => OTPType::TRANSFER_CODE])->delete();
        return $this->successMsg(__('auth.transfer_code_checked'));
    }

    public function charge($balance)
    {
        $this->deposit(auth()->user(), $balance);

        return $this->successMsg(__('apis.balance_updated'));
    }
}
