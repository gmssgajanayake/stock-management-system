<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProcessBulkOrdersJob implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    protected $rows;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function handle(): void
    {
        foreach ($this->rows as $row) {

            $subtotal = $row['qty'] * $row['unit_price'];

            $order = Order::create([
                'customer_id' => $row['customer_id'],
                'status' => 'PENDING',
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => 0,
                'grand_total' => $subtotal
            ]);

            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $row['product_id'],
                'qty' => $row['qty'],
                'unit_price' => $row['unit_price']
            ]);
        }
    }
}