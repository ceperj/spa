<?php

namespace App\Models;

use App\HashidsRouting;
use App\Models\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory, HashidsRouting, HasStatus;

    protected $fillable = ['name', 'status'];

    protected $appends = [ 'hashid' ];
}
