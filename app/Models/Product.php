<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'sku',
        'name',
        'price',
        'stock',
        'is_active',
        'image',
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
}
