<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseCommittee extends Model
{
    use HasFactory;

    protected $fillable = ['name','description'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'committee_user', 'committee_id', 'user_id');
    }
}
