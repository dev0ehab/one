<?php


namespace App\Models\Helpers;

use App\Http\Resources\ClientResource;

trait ClientHelpers
{
    /**
     * Get the resource for user.
     *
     * @return ClientResource
     */
    public function getResource()
    {
        return new ClientResource($this);
    }



}
