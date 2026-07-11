<?php

namespace App\Services\Entity;

use App\Models\IntroHowWork;

class IntroHowWorkService extends BaseService
{
    /**
     * Initializes the ImageService class.
     *
     * @param IntroHowWork $introHowWork
     */
    public function __construct(IntroHowWork $introHowWork)
    {
        $this->model = $introHowWork;
    }
}
