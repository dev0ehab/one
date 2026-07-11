<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Provider;
use App\Models\WalletChargeRequest;
use App\Models\WalletWithdrawalRequest;
use Illuminate\Database\Seeder;

class WalletRequestSeeder extends Seeder
{
    public function run(): void
    {
        $clientWallet = Client::with('wallet')->find(1)?->wallet;
        $providerWallet = Provider::with('wallet')->find(1)?->wallet;

        if ($clientWallet) {
            WalletChargeRequest::create([
                'wallet_id' => $clientWallet->id,
                'amount'    => 300,
                'status'    => 'pending',
                'image'     => 'default.png',
            ]);
        }

        if ($providerWallet) {
            WalletWithdrawalRequest::create([
                'wallet_id' => $providerWallet->id,
                'amount'    => 150,
                'status'    => 'pending',
            ]);
        }
    }
}
