<?php

namespace App\Http\Resources\Api\Client\Wallets;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

class TransfersResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'sender' => $this->operation == 'deposit' ? $this->clientable->name : auth()->user()->name,
            'receiver' => $this->operation == 'withdrawal' ? $this->clientable->name : auth()->user()->name,
            'date' => Carbon::create($this->created_at)->format('Y-m-d'),
            'time' => Carbon::create($this->created_at)->format('H:i'),
        ];
    }
}