<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Favorite;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::find(1);

        if (!$client) {
            return;
        }

        foreach (Provider::where('is_approved', true)->limit(2)->get() as $provider) {
            Favorite::firstOrCreate([
                'client_id'         => $client->id,
                'favoritable_id'    => $provider->id,
                'favoritable_type'  => Provider::class,
            ]);
        }
    }
}
