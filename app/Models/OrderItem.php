<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'qty',
        'unit_price',
    ];

    // This item belongs to a specific Order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // This item represents a specific Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
