<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    // Fields that can be mass-assigned
    protected $fillable = ['name', 'slug'];

    // Relationship: one category has many products
    public function products()
    {
        return $this->hasMany(Product::class);
    }

}
