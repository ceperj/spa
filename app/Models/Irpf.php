<?php

namespace App\Models;

use App\HashidsRouting;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Irpf extends Model
{
    use HasFactory, SoftDeletes, HashidsRouting;

    protected $fillable = ['min_cents', 'max_cents', 'aliquot', 'user_id'];

    protected $appends = ['hashid'];

    public function user()
    {
        return $this->belongsTo('user');
    }
}
