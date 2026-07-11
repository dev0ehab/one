<?php

namespace App\Services\Entity;

use App\Models\Fqs;

class FAQsService extends BaseService
{
    /**
     * Initializes the FAQsService class.
     *
     * @param Fqs $fq The country model instance.
     */
    public function __construct(Fqs $fq)
    {
        $this->model = $fq;
    }
}
