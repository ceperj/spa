<?php

namespace App\Models;

use App\HashidsRouting;
use App\Models\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes, HashidsRouting, HasStatus;

    protected $fillable = [
        'projectName',
        'sector',
        'process',
        'projectManager',
        'startDate',
        'endDate',
    ];

    protected $appends = [
        'hashid',
    ];
}
