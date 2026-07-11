<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Provider;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    public function run(): void
    {
        foreach (Client::all() as $index => $client) {
            $balance = $index === 0 ? 1500 : 200;

            if ($client->wallet) {
                $client->wallet->update(['balance' => $balance]);
            } else {
                $client->wallet()->create(['balance' => $balance]);
            }
        }

        foreach (Provider::all() as $index => $provider) {
            $balance = match ($index) {
                0       => 800,
                1       => 1200,
                default => 0,
            };

            if ($provider->wallet) {
                $provider->wallet->update(['balance' => $balance]);
            } else {
                $provider->wallet()->create(['balance' => $balance]);
            }
        }
    }
}
