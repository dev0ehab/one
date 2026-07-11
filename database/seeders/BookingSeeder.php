<?php

namespace Database\Seeders;

use App\Enums\AdditionalInvoiceStatusEnum;
use App\Enums\BookingStatusEnum;
use App\Models\Booking;
use App\Models\Client;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::find(1);
        $provider1 = Provider::find(1);
        $provider2 = Provider::find(2);

        if (!$client || !$provider1) {
            $this->command?->warn('BookingSeeder: seed clients and providers first.');

            return;
        }

        $base = [
            'client_id' => $client->id,
            'provider_id' => $provider1->id,
            'scheduled_at' => now()->addDays(2),
            'service_title' => 'فحص طبي',
            'service_description' => 'فحص دوري في العيادة',
            'price' => 250,
            'address' => 'الرياض — حي العليا',
        ];

        Booking::create(array_merge($base, [
            'status' => BookingStatusEnum::NEW,
        ]));

        Booking::create(array_merge($base, [
            'status' => BookingStatusEnum::PENDING_PAYMENT,
            'scheduled_at' => now()->addDays(3),
            'service_title' => 'استشارة',
            'price' => 180,
        ]));

        if ($provider2) {
            Booking::create([
                'client_id' => $client->id,
                'provider_id' => $provider2->id,
                'status' => BookingStatusEnum::IN_PROGRESS,
                'scheduled_at' => now()->subHours(2),
                'service_title' => 'علاج أسنان',
                'service_description' => 'جلسة علاج',
                'price' => 400,
                'payment_method' => 'wallet',
                'paid_at' => now()->subHour(),
                'address' => 'جدة',
            ]);
        }

        Booking::create(array_merge($base, [
            'status' => BookingStatusEnum::COMPLETED,
            'scheduled_at' => now()->subDays(3),
            'service_title' => 'متابعة',
            'price' => 300,
            'payment_method' => 'online',
            'paid_at' => now()->subDays(2),
        ]));

        Booking::create(array_merge($base, [
            'status' => BookingStatusEnum::CANCELLED,
            'scheduled_at' => now()->addDays(5),
            'service_title' => 'موعد ملغى',
            'price' => 200,
            'cancel_reason' => 'طلب العميل الإلغاء',
        ]));

        $client2 = Client::find(2);
        if ($client2) {
            Booking::create([
                'client_id' => $client2->id,
                'provider_id' => $provider1->id,
                'status' => BookingStatusEnum::IN_PROGRESS,
                'scheduled_at' => now()->addDay(),
                'service_title' => 'فحص مختبر',
                'price' => 150,
                'payment_method' => 'wallet',
                'paid_at' => now(),
                'address' => 'الرياض',
                'additional_invoice_amount' => 75,
                'additional_invoice_status' => AdditionalInvoiceStatusEnum::PENDING,
                'additional_invoice_reason' => 'تحاليل إضافية',
            ]);
        }
    }
}
