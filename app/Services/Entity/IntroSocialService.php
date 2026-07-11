<?php

namespace App\Services\Entity;


use App\Models\IntroSocial;

class IntroSocialService extends BaseService
{
    /**
     * Initializes the IntroSliderService class.
     *
     * @param IntroSocial $introSocial
     */
    public function __construct(IntroSocial $introSocial)
    {
        $this->model = $introSocial;
    }
}
