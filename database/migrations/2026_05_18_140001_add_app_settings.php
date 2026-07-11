<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $settings = [
            ['key' => 'inquiry_radius_km', 'value' => '20'],
            ['key' => 'additional_invoice_payment_hours', 'value' => '72'],
            ['key' => 'additional_invoice_commission_percent', 'value' => '10'],
            ['key' => 'wallet_low_balance_threshold', 'value' => '50'],
            ['key' => 'booking_service_monthly_fee', 'value' => '0'],
            ['key' => 'booking_service_duration_days', 'value' => '30'],
        ];

        foreach ($settings as $setting) {
            if (!DB::table('site_settings')->where('key', $setting['key'])->exists()) {
                DB::table('site_settings')->insert([
                    'key'        => $setting['key'],
                    'value'      => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'inquiry_radius_km',
            'additional_invoice_payment_hours',
            'additional_invoice_commission_percent',
            'wallet_low_balance_threshold',
            'booking_service_monthly_fee',
            'booking_service_duration_days',
        ])->delete();
    }
};
