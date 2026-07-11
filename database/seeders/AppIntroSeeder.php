<?php

namespace Database\Seeders;

use App\Models\AppIntro;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppIntroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Client type app intros
        AppIntro::create([
            'title' => [
                'en' => 'Welcome to PetroWallet',
                'ar' => 'مرحباً بك في PetroWallet'
            ],
            'description' => [
                'en' => 'Manage your fuel transactions and payments with ease. Track your expenses and enjoy seamless digital payments.',
                'ar' => 'أدر معاملات الوقود والمدفوعات بسهولة. تتبع مصروفاتك واستمتع بالمدفوعات الرقمية السلسة.'
            ],
            'type' => 'client',
            'image' => 'intro-images/client-welcome.png'
        ]);

        AppIntro::create([
            'title' => [
                'en' => 'Secure Payments',
                'ar' => 'مدفوعات آمنة'
            ],
            'description' => [
                'en' => 'Your financial data is protected with bank-level security. Make payments with confidence.',
                'ar' => 'بياناتك المالية محمية بأمان على مستوى البنوك. ادفع بثقة.'
            ],
            'type' => 'client',
            'image' => 'intro-images/client-security.png'
        ]);

        AppIntro::create([
            'title' => [
                'en' => 'Everything you need',
                'ar' => 'كل ما تحتاجه.. بين يديك',
            ],
            'description' => [
                'en' => 'Discover offers, inquire, and book your services easily.',
                'ar' => 'اكتشف العروض واستفسر واحجز خدماتك بسهولة.',
            ],
            'type' => 'provider',
            'image' => 'intro-images/provider-onboarding-1.png',
        ]);

        AppIntro::create([
            'title' => [
                'en' => 'Discover services easily',
                'ar' => 'اكتشف الخدمات بسهولة',
            ],
            'description' => [
                'en' => 'Offers, inquiries, and booking — everything you need in one place.',
                'ar' => 'العروض والاستفسارات والحجز.. كل ما تحتاج بين يديك',
            ],
            'type' => 'provider',
            'image' => 'intro-images/provider-onboarding-2.png',
        ]);

    }
}
