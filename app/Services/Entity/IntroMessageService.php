<?php

namespace App\Services\Entity;

use App\Models\IntroMessages;

class IntroMessageService extends BaseService
{
    /**
     * Initializes the ImageService class.
     *
     * @param IntroMessages $introMessages
     */
    public function __construct(IntroMessages $introMessages)
    {
        $this->model = $introMessages;
    }
}
