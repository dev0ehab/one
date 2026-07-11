<?php

namespace Database\Seeders;

use App\Enums\WalletTransactionType;
use App\Models\Client;
use App\Models\Provider;
use App\Models\WalletTransaction;
use Illuminate\Database\Seeder;

class WalletTransactionSeeder extends Seeder
{
    public function run(): void
    {
        $client = Client::with('wallet')->find(1);
        $provider = Provider::with('wallet')->find(1);

        if ($client?->wallet) {
            $this->seedHistory($client->wallet->id, $client->id, Client::class, [
                ['amount' => 500, 'operation' => 'deposit', 'type' => WalletTransactionType::CHARGE],
                ['amount' => 100, 'operation' => 'withdrawal', 'type' => WalletTransactionType::BOOKING],
            ]);
        }

        if ($provider?->wallet) {
            $this->seedHistory($provider->wallet->id, $provider->id, Provider::class, [
                ['amount' => 200, 'operation' => 'deposit', 'type' => WalletTransactionType::CHARGE],
            ]);
        }
    }

    private function seedHistory(int $walletId, int $clientableId, string $clientableType, array $rows): void
    {
        foreach ($rows as $row) {
            WalletTransaction::withoutEvents(function () use ($walletId, $clientableId, $clientableType, $row) {
                WalletTransaction::create([
                    'wallet_id'        => $walletId,
                    'amount'           => $row['amount'],
                    'operation'        => $row['operation'],
                    'type'             => $row['type'],
                    'clientable_id'    => $clientableId,
                    'clientable_type'  => $clientableType,
                ]);
            });
        }
    }
}
