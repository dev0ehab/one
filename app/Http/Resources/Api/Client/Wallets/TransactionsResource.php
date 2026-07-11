<?php

namespace App\Http\Resources\Api\Client\Wallets;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TransactionsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        $isCredit = $this->operation === 'deposit';

        return [
            'id'             => $this->id,
            'amount'         => round((float) $this->amount, 2),
            'is_credit'      => $isCredit,
            'operation'      => __('apis.wallet_operations.' . $this->operation),
            'operation_key'  => $this->operation,
            'type'           => __('apis.wallet_types.' . $this->type),
            'type_key'       => $this->type,
            'date'           => Carbon::parse($this->created_at)->format('Y-m-d'),
            'time'           => Carbon::parse($this->created_at)->format('H:i'),
        ];
    }
}