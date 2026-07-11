<?php

namespace App\Services\Entity;

use App\Enums\WalletTransactionType;
use App\Models\Provider;
use Illuminate\Support\Facades\DB;

class BookingServiceSettingService
{
    public function monthlyFee(): float
    {
        return (float) (settings()['booking_service_monthly_fee'] ?? 0);
    }

    public function durationDays(): int
    {
        return max(1, (int) (settings()['booking_service_duration_days'] ?? 30));
    }

    public function show(Provider $provider): Provider
    {
        $this->syncExpiredState($provider);

        return $provider->fresh(['workDays']);
    }

    public function subscribe(Provider $provider, string $paymentMethod): array
    {
        $this->syncExpiredState($provider);
        $provider->refresh();

        if ($provider->hasActiveBookingService()) {
            return ['status' => 'fail', 'message' => __('apis.booking_service_already_active')];
        }

        $fee = $this->monthlyFee();

        if ($fee > 0 && $paymentMethod === 'wallet') {
            $wallet = $provider->wallet;
            if (!$wallet || $wallet->balance < $fee) {
                return ['status' => 'fail', 'message' => __('apis.not_enough_balance')];
            }
        }

        if ($fee > 0 && $paymentMethod === 'online') {
            return ['status' => 'fail', 'message' => __('apis.online_payment_not_available')];
        }

        DB::beginTransaction();
        try {
            if ($fee > 0 && $paymentMethod === 'wallet') {
                $wallet = $provider->wallet;
                $wallet->transactions()->create([
                    'amount'    => $fee,
                    'operation' => 'withdrawal',
                    'type'      => WalletTransactionType::BOOKING,
                ]);
                $wallet->decrement('balance', $fee);
            }

            $expiresAt = now()->addDays($this->durationDays());

            $provider->update([
                'has_booking_service'        => true,
                'booking_service_expires_at' => $expiresAt,
            ]);

            DB::commit();

            return [
                'status'  => 'success',
                'message' => __('apis.booking_service_subscribed'),
                'data'    => $provider->fresh(['workDays']),
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return ['status' => 'fail', 'message' => __('apis.something_went_wrong')];
        }
    }

    public function update(Provider $provider, array $data): array
    {
        $this->syncExpiredState($provider);
        $provider->refresh();

        if (!$provider->hasActiveBookingService()) {
            return ['status' => 'fail', 'message' => __('apis.booking_service_not_subscribed')];
        }

        DB::beginTransaction();
        try {
            $updates = [];
            if (array_key_exists('service_duration_minutes', $data)) {
                $updates['service_duration_minutes'] = $data['service_duration_minutes'];
            }
            if (array_key_exists('booking_notes', $data)) {
                $updates['booking_notes'] = $data['booking_notes'];
            }
            if (array_key_exists('booking_reminder_minutes', $data)) {
                $updates['booking_reminder_minutes'] = $data['booking_reminder_minutes'];
            }
            if (array_key_exists('show_in_offers', $data)) {
                $updates['show_in_offers'] = $data['show_in_offers'];
            }
            if (array_key_exists('discount_percentage', $data)) {
                $updates['discount_percentage'] = $data['discount_percentage'];
            }
            if (array_key_exists('cancellation_policy', $data)) {
                $updates['cancellation_policy'] = $data['cancellation_policy'];
            }

            if ($updates !== []) {
                $provider->update($updates);
            }

            if (!empty($data['work_days'])) {
                $provider->workDays()->delete();
                $provider->workDays()->createMany($data['work_days']);
            }

            DB::commit();

            return [
                'status'  => 'success',
                'message' => __('apis.booking_service_updated'),
                'data'    => $provider->fresh(['workDays']),
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);

            return ['status' => 'fail', 'message' => __('apis.something_went_wrong')];
        }
    }

    public function syncExpiredState(Provider $provider): void
    {
        if (!$provider->has_booking_service) {
            return;
        }

        if ($provider->booking_service_expires_at && $provider->booking_service_expires_at->isPast()) {
            $provider->update([
                'has_booking_service'        => false,
                'booking_service_expires_at' => null,
            ]);
        }
    }
}
