<?php

namespace App\Services\Entity;

use App\Enums\AdStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Enums\WalletTransactionType;
use App\Models\Package;
use App\Models\Provider;
use App\Models\ProviderAd;
use App\Models\ProviderAdImage;
use App\Models\ProviderSubscription;
use App\Services\Notification\UserNotifier;
use Illuminate\Support\Facades\DB;

class AdService
{

    // ─────────────────────────── Package helpers ──────────────────────────

    public function listPackages()
    {
        return Package::active()->orderBy('sort')->orderBy('price')->get();
    }

    // ─────────────────────────── Subscription ─────────────────────────────

    public function subscribe(Provider $provider, Package $package, string $paymentMethod): array
    {
        if ($package->price > 0 && $paymentMethod === 'wallet') {
            $wallet = $provider->wallet;
            if (!$wallet || $wallet->balance < $package->price) {
                return ['status' => 'fail', 'message' => __('apis.not_enough_balance')];
            }
        }

        DB::beginTransaction();
        try {
            // Deduct wallet if payment is by wallet
            if ($package->price > 0 && $paymentMethod === 'wallet') {
                $wallet = $provider->wallet;
                $wallet->transactions()->create([
                    'amount'    => $package->price,
                    'operation' => 'withdrawal',
                    'type'      => WalletTransactionType::BOOKING,
                ]);
                $wallet->decrement('balance', $package->price);
            }

            $now = now();
            $subscription = ProviderSubscription::create([
                'provider_id'    => $provider->id,
                'package_id'     => $package->id,
                'paid_amount'    => $package->price,
                'payment_method' => $paymentMethod,
                'status'         => SubscriptionStatusEnum::ACTIVE,
                'ads_remaining'  => $package->ads_count,
                'starts_at'      => $now,
                'ends_at'        => $now->copy()->addDays($package->duration_days),
            ]);

            DB::commit();

            return [
                'status' => 'success',
                'message' => __('apis.subscription_created'),
                'data'   => $subscription->load('package'),
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return ['status' => 'fail', 'message' => __('apis.something_went_wrong')];
        }
    }

    public function activeSubscription(Provider $provider): ?ProviderSubscription
    {
        return ProviderSubscription::where('provider_id', $provider->id)
            ->where('status', SubscriptionStatusEnum::ACTIVE)
            ->where('ends_at', '>', now())
            ->with('package')
            ->latest()
            ->first();
    }

    // ─────────────────────────── Ads ──────────────────────────────────────

    public function listMyAds(Provider $provider)
    {
        return ProviderAd::where('provider_id', $provider->id)
            ->with('images')
            ->latest()
            ->paginate(request('perPage', 15));
    }

    public function createAd(Provider $provider, array $data): array
    {
        $subscription = $this->activeSubscription($provider);

        if (!$subscription) {
            return ['status' => 'fail', 'message' => __('apis.no_active_subscription')];
        }

        if (!$subscription->hasAdsRemaining()) {
            return ['status' => 'fail', 'message' => __('apis.ads_limit_reached')];
        }

        DB::beginTransaction();
        try {
            $ad = ProviderAd::create([
                'provider_id'              => $provider->id,
                'provider_subscription_id' => $subscription->id,
                'title'                    => $data['title'],
                'description'              => $data['description'] ?? null,
                'start_at'                 => $data['start_at'],
                'price'                    => $data['price'],
                'status'                   => AdStatusEnum::PENDING,
            ]);

            if (!empty($data['images'])) {
                foreach ($data['images'] as $image) {
                    ProviderAdImage::create([
                        'provider_ad_id' => $ad->id,
                        'image'          => $image,
                    ]);
                }
            }

            // Decrement remaining ads quota
            $subscription->decrement('ads_remaining');

            DB::commit();

            return [
                'status'  => 'success',
                'message' => __('apis.ad_created'),
                'data'    => $ad->load('images'),
            ];
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            return ['status' => 'fail', 'message' => __('apis.something_went_wrong')];
        }
    }

    public function showAd(Provider $provider, int $adId): array
    {
        $ad = ProviderAd::where('provider_id', $provider->id)->with('images')->find($adId);
        if (!$ad) {
            return ['status' => 'fail', 'message' => __('apis.not_found')];
        }
        return ['status' => 'success', 'data' => $ad];
    }

    public function deleteAd(Provider $provider, int $adId): array
    {
        $ad = ProviderAd::where('provider_id', $provider->id)->find($adId);
        if (!$ad) {
            return ['status' => 'fail', 'message' => __('apis.not_found')];
        }

        // Only pending or rejected ads can be deleted; approved active ones require expiry
        $ad->delete();

        return ['status' => 'success', 'message' => __('apis.deleted')];
    }

    // ─────────────────────────── Admin actions ────────────────────────────

    public function approveAd(int $adId): array
    {
        $ad = ProviderAd::with('provider')->find($adId);
        if (!$ad) {
            return ['status' => 'fail', 'message' => __('apis.not_found')];
        }
        if ($ad->status !== AdStatusEnum::PENDING) {
            return ['status' => 'fail', 'message' => __('apis.invalid_status')];
        }

        $ad->update([
            'status'      => AdStatusEnum::APPROVED,
            'approved_at' => now(),
            'reject_reason' => null,
        ]);

        UserNotifier::send(
            $ad->provider,
            'ad_approved',
            ProviderAd::class,
            $ad->id,
            ['title' => $ad->title]
        );

        return ['status' => 'success', 'message' => __('apis.ad_approved'), 'data' => $ad];
    }

    public function rejectAd(int $adId, string $reason): array
    {
        $ad = ProviderAd::with('provider')->find($adId);
        if (!$ad) {
            return ['status' => 'fail', 'message' => __('apis.not_found')];
        }
        if ($ad->status !== AdStatusEnum::PENDING) {
            return ['status' => 'fail', 'message' => __('apis.invalid_status')];
        }

        $ad->update([
            'status'        => AdStatusEnum::REJECTED,
            'reject_reason' => $reason,
        ]);

        // Refund the ad slot to the subscription
        if ($ad->subscription) {
            $ad->subscription->increment('ads_remaining');
        }

        UserNotifier::send(
            $ad->provider,
            'ad_rejected',
            ProviderAd::class,
            $ad->id,
            ['title' => $ad->title, 'reason' => $reason]
        );

        return ['status' => 'success', 'message' => __('apis.ad_rejected'), 'data' => $ad];
    }

    // ─────────────────────────── Client listing ───────────────────────────

    public function listApprovedAds()
    {
        $perPage = (int) request('perPage', request('paginate', 15));
        $page = max(1, (int) request('page', 1));

        $ads = ProviderAd::active()
            ->with(['images', 'provider:id,trade_name,image,avg_rate'])
            ->latest('approved_at')
            ->paginate($perPage, ['*'], 'page', $page);

        $this->recordAdViewsForPage($page, $perPage);

        return $ads;
    }

    public function showApprovedAd(int $adId): array
    {
        $ad = ProviderAd::active()
            ->with(['images', 'provider:id,trade_name,image,avg_rate'])
            ->find($adId);

        if (!$ad) {
            return ['status' => 'fail', 'message' => __('apis.not_found')];
        }

        $ad->increment('clicks');
        $ad->refresh();

        return ['status' => 'success', 'data' => $ad];
    }

    /**
     * Page 1: count views for the requested page.
     * Page 2+: count views for the previous page the client had already loaded.
     */
    public function recordAdViewsForPage(int $page, int $perPage, ?\Closure $scope = null): void
    {
        $viewPage = $page > 1 ? $page - 1 : $page;

        $query = ProviderAd::active()->latest('approved_at');
        if ($scope) {
            $query = $scope($query);
        }

        $this->recordAdViewsForIds(
            $query->forPage($viewPage, $perPage)->pluck('id')
        );
    }

    public function recordAdViewsForIds(iterable $ids): void
    {
        $ids = collect($ids)->filter()->unique()->values();

        if ($ids->isNotEmpty()) {
            ProviderAd::whereIn('id', $ids)->increment('views');
        }
    }
}
