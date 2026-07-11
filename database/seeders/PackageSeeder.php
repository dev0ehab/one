<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name'         => ['ar' => 'الباقة الأساسية', 'en' => 'Basic Package'],
                'description'  => ['ar' => 'أضف إعلاناً واحداً لمدة شهر', 'en' => 'Add 1 ad for one month'],
                'price'        => 50.00,
                'ads_count'    => 1,
                'duration_days'=> 30,
                'is_active'    => true,
                'sort'         => 1,
            ],
            [
                'name'         => ['ar' => 'الباقة المتوسطة', 'en' => 'Standard Package'],
                'description'  => ['ar' => 'أضف 3 إعلانات لمدة شهر', 'en' => 'Add 3 ads for one month'],
                'price'        => 120.00,
                'ads_count'    => 3,
                'duration_days'=> 30,
                'is_active'    => true,
                'sort'         => 2,
            ],
            [
                'name'         => ['ar' => 'الباقة المميزة', 'en' => 'Premium Package'],
                'description'  => ['ar' => 'أضف 10 إعلانات لمدة شهرين', 'en' => 'Add 10 ads for two months'],
                'price'        => 350.00,
                'ads_count'    => 10,
                'duration_days'=> 60,
                'is_active'    => true,
                'sort'         => 3,
            ],
        ];

        foreach ($packages as $data) {
            Package::firstOrCreate(['name->ar' => $data['name']['ar']], $data);
        }
    }
}
