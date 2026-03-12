<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderData;

    /**
     * Create a new job instance.
     */
    public function __construct(array $orderData)
    {
        $this->orderData = $orderData;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Example: Create order in database
        Order::create([
            'customer_name' => $this->orderData['customer_name'],
            'sku' => $this->orderData['sku'],
            'qty' => $this->orderData['qty'],
            'discount' => $this->orderData['discount'],
            'tax' => $this->orderData['tax'],
        ]);

        // You can also trigger notifications, stock updates, etc.
    }
}