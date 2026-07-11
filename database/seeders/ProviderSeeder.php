<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Provider;
use App\Enums\CategoryTypeEnum;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::query()
            ->where('type', CategoryTypeEnum::PROVIDER)
            ->whereNull('parent_id')
            ->get();

        if ($categories->isEmpty()) {
            $this->command?->warn('ProviderSeeder: run CategorySeeder first.');

            return;
        }

        $providers = [
            [
                'trade_name'              => 'عيادة النور',
                'owner_name'              => 'د. أحمد علي',
                'phone'                   => '512345679',
                'country_code'            => '966',
                'email'                   => 'provider1@example.com',
                'password'                => 'Password123',
                'national_id'             => '1234567890',
                'commercial_register_num' => '1234567890123',
                'image'                   => 'default.png',
                'city_id'                 => 1,
                'lat'                     => 24.7136,
                'lng'                     => 46.6753,
                'map_desc'                => 'الرياض — حي العليا',
                'start_time'              => '08:00',
                'end_time'                => '22:00',
                'banners'                 => ['default.png'],
                'bank_name'               => 'Al Rajhi Bank',
                'account_name'            => 'Provider Account 1',
                'account_num'             => '1234567890',
                'iban'                    => 'SA03000000000000000001',
                'category_id'             => $categories->first()->id,
                'is_active'               => true,
                'is_approved'             => true,
                'has_booking_service'        => true,
                'booking_service_expires_at' => now()->addYear(),
                'service_duration_minutes'   => 30,
                'booking_notes'              => 'يرجى الحضور قبل الموعد بـ 10 دقائق.',
                'avg_rate'                   => 4.5,
            ],
            [
                'trade_name'              => 'مركز منى الطبي',
                'owner_name'              => 'د. منى سالم',
                'phone'                   => '512345670',
                'country_code'            => '966',
                'email'                   => 'provider2@example.com',
                'password'                => 'Password123',
                'national_id'             => '1234567891',
                'commercial_register_num' => '1234567890124',
                'image'                   => 'default.png',
                'city_id'                 => 2,
                'lat'                     => 21.3891,
                'lng'                     => 39.8579,
                'map_desc'                => 'جدة — الكورنيش',
                'start_time'              => '09:00',
                'end_time'                => '21:00',
                'banners'                 => ['default.png'],
                'bank_name'               => 'SNB',
                'account_name'            => 'Provider Account 2',
                'account_num'             => '0987654321',
                'iban'                    => 'SA03000000000000000002',
                'category_id'             => $categories->last()->id,
                'is_active'               => true,
                'is_approved'             => true,
                'has_booking_service'        => true,
                'booking_service_expires_at' => now()->addYear(),
                'service_duration_minutes'   => 30,
                'booking_notes'              => 'يرجى إحضار الهوية عند الزيارة.',
                'avg_rate'                   => 4.0,
            ],
        ];

        foreach ($providers as $providerData) {
            Provider::updateOrCreate(
                ['phone' => $providerData['phone'], 'country_code' => $providerData['country_code']],
                $providerData
            );
        }
    }
}
