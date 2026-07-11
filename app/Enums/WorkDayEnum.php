<?php

namespace App\Enums;

class WorkDayEnum extends Base
{
    public const SATURDAY    = 'saturday';
    public const SUNDAY      = 'sunday';
    public const MONDAY      = 'monday';
    public const TUESDAY     = 'tuesday';
    public const WEDNESDAY   = 'wednesday';
    public const THURSDAY    = 'thursday';
    public const FRIDAY      = 'friday';

    /**
     * @return array{ar: string, en: string}
     */
    public static function defaultNames(string $day): array
    {
        return match ($day) {
            self::SATURDAY    => ['ar' => 'السبت', 'en' => 'Saturday'],
            self::SUNDAY      => ['ar' => 'الأحد', 'en' => 'Sunday'],
            self::MONDAY      => ['ar' => 'الإثنين', 'en' => 'Monday'],
            self::TUESDAY     => ['ar' => 'الثلاثاء', 'en' => 'Tuesday'],
            self::WEDNESDAY   => ['ar' => 'الأربعاء', 'en' => 'Wednesday'],
            self::THURSDAY    => ['ar' => 'الخميس', 'en' => 'Thursday'],
            self::FRIDAY      => ['ar' => 'الجمعة', 'en' => 'Friday'],
            default           => ['ar' => '', 'en' => ''],
        };
    }
}

