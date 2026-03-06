<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Teacher extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'teachers';

    protected $fillable = ['name', 'email', 'mobile_number', 'password', 'email_verified_at', 'department_id'];

    protected $hidden = ['password', 'remember_token'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function getRoleAttribute()
    {
        return 'teacher';
    }

    public function hasVerifiedEmail()
    {
        return !is_null($this->email_verified_at);
    }

    public function isTeacher()
    {
        return true;
    }

    public function isHod()
    {
        return false;
    }

    public function isPrincipal()
    {
        return false;
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
