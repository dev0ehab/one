<?php

namespace App\Http\Resources\Api\Client\Wallets;

use App\Traits\PaginationTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class TransfersCollection extends ResourceCollection
{
    use PaginationTrait;
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request    
     * @return array
     */
    public function toArray($request): array
    {
        return [

            'pagination' => $this->paginationModel($this),
            'data'       => TransfersResource::collection($this->collection),
        ];
    }
}