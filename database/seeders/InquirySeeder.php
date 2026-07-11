<?php

namespace Database\Seeders;

use App\Enums\InquiryRecipientStatusEnum;
use App\Enums\InquiryStatusEnum;
use App\Enums\InquiryTargetTypeEnum;
use App\Models\Category;
use App\Models\Client;
use App\Models\Inquiry;
use App\Models\InquiryProvider;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::find(1);
        $category = Category::query()->whereNull('parent_id')->first();
        $providers = Provider::where('is_approved', true)->limit(2)->get();

        if (!$client || !$category || $providers->isEmpty()) {
            $this->command?->warn('InquirySeeder: seed clients, categories, and providers first.');

            return;
        }

        $pending = Inquiry::create([
            'client_id'    => $client->id,
            'category_id'  => $category->id,
            'title'        => 'استفسار عن خدمة التقويم',
            'description'  => 'أحتاج عرض سعر لعلاج تقويم الأسنان لمدة سنة.',
            'target_type'  => InquiryTargetTypeEnum::ALL,
            'status'       => InquiryStatusEnum::PENDING,
            'lat'          => 24.7136,
            'lng'          => 46.6753,
            'address'      => 'الرياض',
        ]);

        foreach ($providers as $provider) {
            InquiryProvider::create([
                'inquiry_id'  => $pending->id,
                'provider_id' => $provider->id,
                'status'      => InquiryRecipientStatusEnum::PENDING,
            ]);
        }

        $acceptedProvider = $providers->first();

        Inquiry::create([
            'client_id'             => $client->id,
            'category_id'           => $category->id,
            'title'                 => 'استفسار عن تنظيف الأسنان',
            'description'           => 'هل تتوفر مواعيد مسائية خلال الأسبوع؟',
            'target_type'           => InquiryTargetTypeEnum::NEAREST,
            'status'                => InquiryStatusEnum::ACCEPTED,
            'lat'                   => 24.72,
            'lng'                   => 46.68,
            'address'               => 'الرياض — العليا',
            'accepted_provider_id'  => $acceptedProvider->id,
            'accepted_at'           => now()->subDay(),
        ]);
    }
}
