<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['name','company_name','phone','email','address','gst_number'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
