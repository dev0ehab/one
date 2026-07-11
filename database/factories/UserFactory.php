p<?php

use Faker\Factory as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\User::class, function () {
    $faker                   = Faker::create('ar_SA');
    return [
        'name'               => $faker->name,
        'username'           => $faker->userName,
        'country_code'       => '966',
        'phone'              => $faker->unique()->phoneNumber,
        // 'email'              => $faker->unique()->safeEmail,
        'bio'                => $faker->sentence,
        'city_id'            => \App\Models\City::inRandomOrder()->first()->id,
        'lat'                => $faker->latitude,
        'lng'                => $faker->longitude,
        'map_desc'           => $faker->address,
        'image'              => 'default.png',
        'is_active'          => $faker->boolean,
        'is_blocked'         => $faker->boolean,
        'is_notify'          => $faker->boolean,
        'is_shown'           => $faker->boolean,
        'is_location_shown'  => $faker->boolean,
    ];
});
