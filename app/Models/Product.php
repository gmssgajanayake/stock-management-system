<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    use HasFactory;
    protected $fillable = [
        'sku',
        'name',
        'slug',
        'price',
        'stock',
        'is_active',
        'image',
        'category_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];

    // A Product can be part of many Order Items
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Get all images for a product
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Get just main image
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    // A Product belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
