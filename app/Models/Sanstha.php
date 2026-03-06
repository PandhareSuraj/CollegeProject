<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sanstha extends Model
{
    use HasFactory;

    protected $fillable = ['name','description'];

    public function colleges()
    {
        return $this->hasMany(College::class);
    }
}
