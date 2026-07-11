<?php

namespace Database\Seeders;

use App\Enums\CategoryTypeEnum;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        for ($i = 1; $i <= 2; $i++) {
            Category::create([
                'name' => ['ar' => 'فئة مقدم ' . $i, 'en' => 'Provider Category ' . $i],
                'image' => 'default.png',
                'parent_id' => null,
                'type' => CategoryTypeEnum::PROVIDER,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }
}
