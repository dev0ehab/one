<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    public function run(): void
    {
        $clients = Client::limit(5)->get();

        foreach ($clients as $index => $client) {
            Location::create([
                'client_id'        => $client->id,
                'name'             => $index === 0 ? 'المنزل' : 'موقع ' . ($index + 1),
                'country_id'       => 1,
                'city_id'          => 1,
                'lat'              => 24.7136 + ($index * 0.01),
                'lng'              => 46.6753 + ($index * 0.01),
                'map_desc'         => 'الرياض — موقع ' . ($index + 1),
                'is_main_location' => $index === 0,
            ]);
        }
    }
}
