<?php

namespace Database\Seeders;

use App\Enums\AdStatusEnum;
use App\Enums\SubscriptionStatusEnum;
use App\Models\Package;
use App\Models\Provider;
use App\Models\ProviderAd;
use App\Models\ProviderAdImage;
use App\Models\ProviderSubscription;
use Illuminate\Database\Seeder;

class ProviderAdSeeder extends Seeder
{
    public function run(): void
    {
        $package = Package::query()->where('is_active', true)->orderBy('sort')->first();

        if (!$package) {
            $this->command?->warn('ProviderAdSeeder: run PackageSeeder first.');

            return;
        }

        $providers = Provider::query()
            ->where('is_active', true)
            ->where('is_approved', true)
            ->get();

        if ($providers->isEmpty()) {
            $this->command?->warn('ProviderAdSeeder: run ProviderSeeder first.');

            return;
        }

        $adsCatalog = [
            [
                'title'       => 'عرض خاص للعملاء الجدد',
                'description' => 'خصم 20% على الحجز الأول لكل عميل جديد.',
                'start_at'    => now(),
                'price'       => 150.00,
                'status'      => AdStatusEnum::APPROVED,
            ],
            [
                'title'       => 'باقة الفحص الشامل',
                'description' => 'فحص شامل بسعر مخفّض لفترة محدودة.',
                'start_at'    => now(),
                'price'       => 299.00,
                'status'      => AdStatusEnum::APPROVED,
            ],
            [
                'title'       => 'استشارة مجانية',
                'description' => 'استشارة أولية مجانية عند الحجز عبر التطبيق.',
                'start_at'    => now()->addDay(),
                'price'       => 0,
                'status'      => AdStatusEnum::PENDING,
            ],
        ];

        foreach ($providers as $index => $provider) {
            $subscription = $this->ensureActiveSubscription($provider, $package);

            foreach ($adsCatalog as $adIndex => $adData) {
                // First provider gets all statuses; others only approved ads for the client feed.
                if ($index > 0 && $adData['status'] !== AdStatusEnum::APPROVED) {
                    continue;
                }

                $ad = ProviderAd::firstOrCreate(
                    [
                        'provider_id' => $provider->id,
                        'title'       => $adData['title'],
                    ],
                    [
                        'provider_subscription_id' => $subscription->id,
                        'description'              => $adData['description'],
                        'start_at'                 => $adData['start_at'],
                        'price'                    => $adData['price'],
                        'status'                   => $adData['status'],
                        'approved_at'              => $adData['status'] === AdStatusEnum::APPROVED ? now() : null,
                        'expires_at'               => $adData['status'] === AdStatusEnum::APPROVED
                            ? now()->addDays($package->duration_days)
                            : null,
                    ]
                );

                if ($ad->wasRecentlyCreated && !$ad->images()->exists()) {
                    ProviderAdImage::query()->insert([
                        'provider_ad_id' => $ad->id,
                        'image'          => 'default.png',
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }
            }
        }
    }

    private function ensureActiveSubscription(Provider $provider, Package $package): ProviderSubscription
    {
        $existing = ProviderSubscription::query()
            ->where('provider_id', $provider->id)
            ->where('status', SubscriptionStatusEnum::ACTIVE)
            ->where('ends_at', '>', now())
            ->first();

        if ($existing) {
            return $existing;
        }

        $startsAt = now();

        return ProviderSubscription::create([
            'provider_id'    => $provider->id,
            'package_id'     => $package->id,
            'paid_amount'    => $package->price,
            'payment_method' => 'wallet',
            'status'         => SubscriptionStatusEnum::ACTIVE,
            'ads_remaining'  => max(0, $package->ads_count - 2),
            'starts_at'      => $startsAt,
            'ends_at'        => $startsAt->copy()->addDays($package->duration_days),
        ]);
    }
}
