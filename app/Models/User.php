<?php

namespace App\Models;

use App\Constants;
use App\HashidsRouting;
use App\Models\Traits\HasStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasStatus, HashidsRouting;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'status',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'id',
        'password',
        'remember_token',
    ];

    protected $appends = [
        'hashid',
    ];

    public function isActive(){
        return $this->status === Constants::STATUS_ACTIVE;
    }

    public function isStandard(){
        return $this->role === Constants::USER_ROLE_STANDARD
            || $this->role === Constants::USER_ROLE_ADMIN;
    }

    public function isAdmin(){
        return $this->role === Constants::USER_ROLE_ADMIN;
    }
}
