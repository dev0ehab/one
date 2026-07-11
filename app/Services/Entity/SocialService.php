<?php

namespace App\Services\Entity;

use App\Models\Social;

class SocialService extends BaseService
{
    /**
     * Initializes the IntroService class.
     *
     * @param Social $social
     */
    public function __construct(Social $social)
    {
        $this->model = $social;
    }
}
