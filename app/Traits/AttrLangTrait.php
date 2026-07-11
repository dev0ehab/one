<?php

namespace App\Traits;

use Faker\Factory as Faker;

trait AttrLangTrait
{
    public function getAttribute($attr = "name", $fakerMethod = "name", $wrap = false): array
    {

        $arr = [];
        foreach (languages(true) as $lang) {
            $faker = Faker::create($lang["faker"]);
            $fakeData = $faker->$fakerMethod;

            if ($wrap) {
                $fakeData = "<p>" . $fakeData . "</p>";
            }

            $arr[$lang["code"]] = $fakeData;
        }
        return $arr;

        dd($arr);
    }
}
