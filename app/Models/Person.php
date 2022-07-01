<?php

namespace App\Models;

use App\HashidsRouting;
use App\Models\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory, SoftDeletes, HashidsRouting, HasStatus;

    protected $table = 'persons';

    protected $fillable = [
        'name',
        'cpf',
        'rg',
        'rgexp',
        'pis',
        'phone1',
        'phone2',
        'project_id',
        'bank_id',
        'bank_agency',
        'bank_account',
        'battery_id',
        'email',
        'job_id',
        'admission_date',
        'salary',
        'registration_number',
    ];

    protected $appends = [
        'hashid',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'admission_date' => 'date',
    ];

    public function project(){
        return $this->belongsTo(Project::class);
    }

    public function bank(){
        return $this->belongsTo(Bank::class);
    }

    public function job(){
        return $this->belongsTo(Job::class);
    }

    public function battery(){
        return $this->belongsTo(Battery::class);
    }
}
