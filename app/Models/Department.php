<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['college_id', 'name'];

    public function college()
    {
        return $this->belongsTo(College::class);
    }

    public function teachers()
    {
        return $this->hasMany(Teacher::class);
    }

    public function hods()
    {
        return $this->hasMany(Hod::class);
    }

    public function requests()
    {
        return $this->hasMany(StationaryRequest::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
