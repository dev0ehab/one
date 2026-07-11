<?php

namespace App\Enums;

class InquiryStatusEnum extends Base
{
    public const PENDING   = 'pending';
    public const ACCEPTED  = 'accepted';
    public const REJECTED  = 'rejected';
    public const CANCELLED = 'cancelled';
}
