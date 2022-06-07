<?php

namespace App\Models;

use App\HashidsRouting;
use App\Models\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Battery extends Model
{
    use HasFactory, SoftDeletes, HashidsRouting, HasStatus;

    protected $appends = [ 'hashid' ];

    public function persons(){
        return $this->hasMany(Person::class);
    }
}
