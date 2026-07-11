<?php

namespace App\Models;

use App\Enums\SettlementStatusEnum;
use App\Traits\ModelTrait;
use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Invoice extends BaseModel
{
    use ModelTrait, Filterable;

    protected $fillable = [
        'clientable_id',
        'clientable_type',
        'price',
        'cashback',
        'commission',
        'date',
        'invoice_num',
        'phone',
    ];

    protected $casts = ['price' => 'float', 'cashback' => 'float', 'commission' => 'float'];

    public function clientable(): MorphTo
    {
        return $this->morphTo();
    }

    public function settlements()
    {
        return $this->morphToMany(Settlement::class, 'settlementable')
            ->using(Settlementable::class);
    }

    public function settlementables(): MorphMany
    {
        return $this->morphMany(Settlementable::class, 'settlementable');
    }

    public function scopeNotSettled($query)
    {
        return $query->whereDoesntHave('settlementables.settlement', function ($q) {
            $q->whereIn('status', [
                SettlementStatusEnum::PENDING,
                SettlementStatusEnum::ACCEPTED,
            ]);
        });
    }

    public function getOrderNumberAttribute()
    {
        return (string) $this->id;
    }
}
