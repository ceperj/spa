<?php

namespace App\Models;

use App\HashidsRouting;
use App\Models\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Person job.
 * 
 * Do not confuse with Laravel queue jobs, as they are stored in the "queues"
 * and "failed_jobs" database tables.
 */
class Job extends Model
{
    use HasFactory, HashidsRouting, HasStatus;

    protected $fillable = ['name', 'status'];

    protected $appends = [ 'hashid' ];
}
