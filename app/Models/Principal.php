<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Principal extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'principals';

    protected $fillable = ['name', 'email', 'mobile_number', 'password', 'email_verified_at'];

    protected $hidden = ['password', 'remember_token'];

    public function getRoleAttribute()
    {
        return 'principal';
    }

    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function isTeacher()
    {
        return false;
    }

    public function isHod()
    {
        return false;
    }

    public function isPrincipal()
    {
        return true;
    }

    public function isTrustHead()
    {
        return false;
    }

    public function isProvider()
    {
        return false;
    }

    public function isAdmin()
    {
        return false;
    }
}
