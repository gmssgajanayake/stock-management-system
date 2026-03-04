<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number',
        'customer_id',
        'status', // PENDING, CONFIRMED, CANCELLED, DELIVERED
        'subtotal',
        'discount',
        'tax',
        'grand_total',
    ];

    // Every Order belongs to one Customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // An Order has many individual items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Auto-generate the SO-XXXXX order number before saving
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            $latestOrder = self::latest('id')->first();
            $nextId = $latestOrder ? $latestOrder->id + 1 : 1;
            $order->order_number = 'SO-'.str_pad($nextId, 5, '0', STR_PAD_LEFT);
        });
    }
}
