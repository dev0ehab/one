<?php

namespace Database\Seeders;

use App\Enums\WorkDayEnum;
use App\Models\Provider;
use App\Models\WorkDay;
use Illuminate\Database\Seeder;

class WorkDaySeeder extends Seeder
{
    public function run(): void
    {
        $dayNames = [
            WorkDayEnum::SATURDAY  => ['ar' => 'السبت', 'en' => 'Saturday'],
            WorkDayEnum::SUNDAY    => ['ar' => 'الأحد', 'en' => 'Sunday'],
            WorkDayEnum::MONDAY    => ['ar' => 'الإثنين', 'en' => 'Monday'],
            WorkDayEnum::TUESDAY   => ['ar' => 'الثلاثاء', 'en' => 'Tuesday'],
            WorkDayEnum::WEDNESDAY => ['ar' => 'الأربعاء', 'en' => 'Wednesday'],
            WorkDayEnum::THURSDAY  => ['ar' => 'الخميس', 'en' => 'Thursday'],
            WorkDayEnum::FRIDAY    => ['ar' => 'الجمعة', 'en' => 'Friday'],
        ];

        foreach (Provider::all() as $provider) {
            foreach ($dayNames as $day => $name) {
                WorkDay::create([
                    'userable_id'   => $provider->id,
                    'userable_type' => Provider::class,
                    'day'           => $day,
                    'name'          => $name,
                    'start_at'      => '09:00:00',
                    'end_at'        => '21:00:00',
                ]);
            }
        }
    }
}
