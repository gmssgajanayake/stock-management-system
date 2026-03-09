<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'email'
    ];

    // A Customer can have multiple Orders
    public function orders()
    {
        return $this->hasMany(Order::class);
    }


}
