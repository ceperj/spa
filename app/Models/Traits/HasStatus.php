<?php

namespace App\Models\Traits;

use App\Constants;

trait HasStatus
{
    /**
     * Scope a query to only include active statuses.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    function scopeOnlyActive($query){
        return $query->where('status', Constants::STATUS_ACTIVE);
    }
}