<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessBulkOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function handle()
    {
        foreach ($this->rows as $data) {

            DB::transaction(function () use ($data) {

                // Split customer name
                $customerParts = explode(' ', trim($data['customer_name']), 2);
                $firstName = $customerParts[0] ?? null;
                $lastName = $customerParts[1] ?? null;

                $customer = Customer::where('first_name', $firstName)
                    ->where('last_name', $lastName)
                    ->first();

                $product = Product::where('sku', $data['sku'])->first();

                if (!$customer || !$product) {
                    return;
                }


                Log::debug('Processing order for customer: ' . $customer->first_name . ' ' . $customer->last_name);

                $qty = (int) $data['qty'];
                $discount = (float) $data['discount'];
                $taxPercent = (float) $data['tax'];

                $subtotal = $product->price * $qty;
                $taxAmount = ($subtotal * $taxPercent) / 100;

                $grandTotal = $subtotal - $discount + $taxAmount;

                // Create Order
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'subtotal' => $subtotal,
                    'discount' => $discount,
                    'tax' => $taxAmount,
                    'grand_total' => $grandTotal,
                    'status' => 'PENDING',
                ]);

                // Create Order Item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'unit_price' => $product->price,
                ]);

            });

        }
    }
}
