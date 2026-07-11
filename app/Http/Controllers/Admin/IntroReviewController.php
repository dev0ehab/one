<?php

namespace App\Http\Controllers\Admin;

use App\Traits\ReportTrait;
use App\Services\Entity\IntroReviewService;

class IntroReviewController extends BaseController
{
    use ReportTrait;

    public function __construct(IntroReviewService $introReview)
    {
        parent::__construct($introReview);
    }

    public function changeActivation($id)
    {
        return parent::update($id);
    }
}
