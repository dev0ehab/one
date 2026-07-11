<?php

namespace App\Services\Entity;

use App\Models\IntroReview;

class IntroReviewService extends BaseService
{
    /**
     * Initializes the IntroReviewService class.
     *
     * @param IntroReview $introReview The IntroReview model instance.
     */
    public function __construct(IntroReview $introReview)
    {
        $this->model = $introReview;
    }

    public function indexApi()
    {
        request()->merge([
            "searchArray" => [
                "is_active" => '1',
            ],
        ]);

        return parent::indexApi();
    }
}
