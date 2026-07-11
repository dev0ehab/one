<?php

namespace App\Http\Controllers\Api\Provider;

use App\Http\Controllers\Api\ApiBaseController;
use App\Services\Entity\BranchService;
use App\Traits\ResponseTrait;

class BranchController extends ApiBaseController
{
    use ResponseTrait;


    public function __construct(BranchService $service)
    {
        parent::__construct($service);

        request()->merge(['mine' => true]);

        // $branch_id = request()->route()->parameter('branch');
        // $this->middleware("check-authrization:branches,$branch_id,provider_id,id")->only('show', 'update', 'destroy');
    }

}
