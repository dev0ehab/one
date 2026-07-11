<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class DatabaseSeeder extends Seeder
{
    /**
     * Demo API credentials (after migrate:fresh --seed):
     * - Admin: admin@admin.com / 123456
     * - Client: 966512345678 / Password123
     * - Provider: 966512345679 / Password123
     */
    public function run(): void
    {
        $this->call(SettingSeeder::class);
        $this->call(AdminTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        $this->call(PermissionTableSeeder::class);
        $this->call(SocialTableSeeder::class);
        $this->call(ImageTableSeeder::class);
        $this->call(SmsTableSeeder::class);
        $this->call(PagesTableSeeder::class);
        $this->call(CountrySeeder::class);
        $this->call(CitySeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(PackageSeeder::class);
        $this->call(ProviderSeeder::class);
        $this->call(ProviderAdSeeder::class);
        $this->call(ClientSeeder::class);
        $this->call(LocationSeeder::class);
        $this->call(WorkDaySeeder::class);
        $this->call(WalletSeeder::class);
        $this->call(BookingSeeder::class);
        $this->call(InquirySeeder::class);
        $this->call(FavoriteSeeder::class);
        $this->call(ComplainSeeder::class);
        $this->call(RateSeeder::class);
        $this->call(WalletTransactionSeeder::class);
        $this->call(WalletRequestSeeder::class);

        $this->call(IntroSeeder::class);
        $this->call(AppIntroSeeder::class);
        $this->call(IntroSliderTableSeeder::class);
        $this->call(IntroHowWorkTableSeeder::class);
        $this->call(IntroServiceTableSeeder::class);
        $this->call(IntroFqsCategoryTableSeeder::class);
        $this->call(IntroFqsTableSeeder::class);
        $this->call(IntroPartnerTableSeeder::class);
        $this->call(IntroSocialTableSeeder::class);
        $this->call(FqsTableSeeder::class);
        $this->call(IntroTableSeeder::class);

        $this->refreshSimlinks();
    }

    private function refreshSimlinks(): void
    {
        if (is_link(public_path('storage'))) {
            unlink(public_path('storage'));
        }

        if (file_exists(public_path('storage'))) {
            File::deleteDirectory(public_path('storage'));
        }

        Artisan::call('storage:link');
    }
}
