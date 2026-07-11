<?php

namespace App\Traits;

trait RateTotalsTrait
{
    /**
     * Get the total number of rates.
     *
     * @return int
     */
    public function getTotalRatesAttribute(): int
    {
        return $this->rates()->count();
    }

    /**
     * Get the rate percentage.
     *
     * @return float
     */
    public function getRatePercentageAttribute(): float
    {
        return $this->avg_rate * 20;
    }
}

