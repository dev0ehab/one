<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class PaymentTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_getaway',
        'transaction_id',
        'type',
        'amount',
        'currency_code',
        'status',
        'getaway_response',
        'trans_type',
        'trans_id',
    ];

    /**
     * Get the parent transable model.
     * @return MorphTo
     */
    public function trans(): MorphTo
    {
        return $this->morphTo();
    }
}
