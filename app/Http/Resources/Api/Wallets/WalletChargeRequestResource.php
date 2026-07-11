<?php

namespace App\Http\Resources\Api\Wallets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletChargeRequestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'amount'     => (float) $this->amount,
            'status'     => $this->status,
            'status_label' => __('apis.wallet_charge_status.' . $this->status),
            'image'      => $this->image,
            'reason'     => $this->reason,
            'created_at' => $this->created_at?->format('Y-m-d H:i'),
        ];
    }
}
