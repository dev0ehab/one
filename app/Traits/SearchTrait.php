<?php

namespace App\Traits;

use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

trait SearchTrait
{
    public function scopeSearch($query, $searchArray = [])
    {
        if (empty($searchArray)) {
            $this->orderByCreatedAt($query);

            return $query;
        }

        $this->resolveSearchQuery($query, $searchArray);

        $this->orderByCreatedAt($query);
    }

    protected function resolveSearchQuery($query, $searchArray)
    {
        $query->where(function ($query) use ($searchArray) {
            $this->resolveSearchArray($searchArray, $query);
        });
    }

    public function resolveSearchArray($searchArray, $query)
    {
        foreach ($searchArray as $key => $value) {
            if (is_null($value)) {
                continue;
            }
            
            if ($this->isIdField($key) || $this->isBoolField($key)) {
                $query->where($key, $value == "null" ? null : $value);
            } elseif ($this->isDateField($key)) {
                $this->applyDateFilter($query, $key, $value);
            } else {
                $this->applyRelationshipFilter($query, $key, $value);
            }
        }
    }

    /**
     * Check if the key is an ID field.
     *
     * @param string $key
     * @return bool
     */
    protected function isIdField(string $key): bool
    {
        return str_contains($key, '_id');
    }

    /**
     * Check if the key is an ID field.
     *
     * @param string $key
     * @return bool
     */
    protected function isBoolField(string $key): bool
    {
        return str_contains($key, 'is_');
    }

    /**
     * Check if the key is for date filtering.
     *
     * @param string $key
     * @return bool
     */
    protected function isDateField(string $key): bool
    {
        return in_array($key, ['created_at_min', 'created_at_max']);
    }


    /**
     * Apply date filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param mixed $value
     */
    protected function applyDateFilter($query, string $key, $value)
    {
        try {
            if ($key === 'created_at_min') {
                $date = Carbon::createFromFormat('m-d-Y', $value)->startOfDay();
                $query->whereDate('created_at', '>=', $date);
            } elseif ($key === 'created_at_max') {
                $date = Carbon::createFromFormat('m-d-Y', $value)->endOfDay();
                $query->whereDate('created_at', '<=', $date);
            }
        } catch (\Exception $e) {
            throw new \InvalidArgumentException("Invalid date format provided: $value. Expected format: MM-DD-YYYY");
        }
    }


    /**
     * Apply relationship filters to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $key
     * @param mixed $value
     */
    protected function applyRelationshipFilter($query, string $key, $value)
    {
        if (str_contains($key, '->')) {
            $parts = explode('->', $key);
            $field = array_pop($parts);
            $relationship = implode('.', $parts);
            
            if (method_exists($this, $parts[0])) {
                $query->whereHas($relationship, function ($query) use ($field, $value) {
                    $query->where($field, 'LIKE', "%{$value}%");
                });
            }
        } elseif (Schema::hasColumn($this->getTable(), $key)) {
            $query->whereRaw("LOWER({$key}) LIKE ?", ['%' . strtolower($value) . '%']);
        }
    }

    protected function orderByCreatedAt($query)
    {
        $query->orderBy(
            'created_at',
            request()->searchArray ? data_get(request()->searchArray, 'order', 'DESC') ?? 'DESC' : 'DESC'
        );
    }
}
