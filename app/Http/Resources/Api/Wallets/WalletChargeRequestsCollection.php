<?php

namespace App\Http\Resources\Api\Wallets;

use App\Traits\PaginationTrait;
use Illuminate\Http\Resources\Json\ResourceCollection;

class WalletChargeRequestsCollection extends ResourceCollection
{
    use PaginationTrait;

    public function toArray($request): array
    {
        return [
            'pagination' => $this->paginationModel($this),
            'data'       => WalletChargeRequestResource::collection($this->collection),
        ];
    }
}
