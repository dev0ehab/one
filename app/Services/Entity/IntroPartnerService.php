<?php

namespace App\Services\Entity;

use App\Models\IntroPartner;

class IntroPartnerService extends BaseService
{
    /**
     * Initializes the IntroPartner class.
     *
     * @param IntroPartner $introPartner The service model instance.
     */
    public function __construct(IntroPartner $introPartner)
    {
        $this->model = $introPartner;
    }
}

