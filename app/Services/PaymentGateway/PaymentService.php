<?php

namespace App\Services\PaymentGateway;

use App\Enums\AdditionalInvoiceStatusEnum;
use App\Enums\BookingStatusEnum;
use App\Enums\OrderStatusEnum;
use App\Enums\PaymentTransactions;
use App\Models\Booking;
use App\Services\Notification\BookingNotificationService;
use App\Models\PaymentTransaction;
use App\Services\Entity\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentService
{
    public static function create()
    {
        return new MyfatoorahService;
    }

    public function createPaymentTransaction(
        $amount,
        $type,
        $transactionable_type,
        $transactionable_id,
        $transaction_id,
        $payemtResponse,
        $paymentName
    ) {
        PaymentTransaction::create([
            'payment_getaway'  => $paymentName,
            'transaction_id'   => $transaction_id,
            'type'             => $type,
            'amount'           => $amount,
            'currency_code'    => 'SAR',
            'status'           => 'pending',
            'getaway_response' => $payemtResponse,
            'trans_type'       => $transactionable_type,
            'trans_id'         => $transactionable_id,
        ]);
    }

    public static function callback(Request $request, $brand_id = null)
    {
        $serviceClass = new MyfatoorahService();
        $data = $serviceClass->getPaymentStatus($request->paymentId);

        return PaymentService::checkPayment($data);
    }

    public static function checkPayment($data)
    {
        $transaction = PaymentTransaction::where(
            'transaction_id',
            $data['transaction_id']
        )->firstOrFail();

        if ($data['status'] == false) {
            $transaction->update(['status' => 'failed']);

            return redirect()->route('payment.status.redirect', [
                'transaction_id' => $data['transaction_id'],
                'status'         => 'failed',
            ]);
        }

        $transaction->update(['status' => 'completed']);

        match ((int) $transaction->type) {
            PaymentTransactions::CHARGEWALLET    => PaymentService::chargeWallet($transaction),
            PaymentTransactions::PAYORDER        => PaymentService::payOrder($transaction),
            PaymentTransactions::PAYBOOKING           => PaymentService::payBooking($transaction),
            PaymentTransactions::PAYADDITIONALINVOICE => PaymentService::payAdditionalInvoice($transaction),
            default                                   => null,
        };

        return redirect()->route('payment.status.redirect', [
            'transaction_id' => $data['transaction_id'],
            'status'         => 'success',
        ]);
    }

    public static function chargeWallet($transaction): void
    {
        $walletService = new WalletService();
        $wallet = $transaction->trans;
        $walletService->deposit($wallet->walletable, $transaction->amount);
    }

    public static function payOrder($transaction): void
    {
        try {
            DB::beginTransaction();

            $order = $transaction->trans;

            if (!$order) {
                DB::rollBack();

                return;
            }

            $order->update([
                'status' => $order->status == OrderStatusEnum::NEW
                    ? OrderStatusEnum::PENDING_PROVIDER_APPROVAL
                    : OrderStatusEnum::PREPARING,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function payBooking($transaction): void
    {
        try {
            DB::beginTransaction();

            $booking = Booking::find($transaction->trans_id);

            if (!$booking || $booking->status !== BookingStatusEnum::PENDING_PAYMENT) {
                DB::rollBack();

                return;
            }

            $booking->update([
                'status'         => BookingStatusEnum::IN_PROGRESS,
                'payment_method' => 'online',
                'paid_at'        => now(),
            ]);

            BookingNotificationService::notifyPaid($booking->fresh());

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function payAdditionalInvoice($transaction): void
    {
        try {
            DB::beginTransaction();

            $booking = Booking::find($transaction->trans_id);

            if (!$booking || $booking->status !== BookingStatusEnum::IN_PROGRESS) {
                DB::rollBack();

                return;
            }

            if (!in_array($booking->additional_invoice_status, AdditionalInvoiceStatusEnum::awaitingPayment(), true)) {
                DB::rollBack();

                return;
            }

            $amount = (float) $booking->additional_invoice_amount;
            $commissionPercent = (float) (settings()['additional_invoice_commission_percent'] ?? 0);
            $commission = round($amount * $commissionPercent / 100, 2);

            $booking->update([
                'additional_invoice_status'         => AdditionalInvoiceStatusEnum::PAID,
                'additional_invoice_paid_at'        => now(),
                'additional_invoice_payment_method' => 'online',
                'additional_invoice_commission'     => $commission,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
