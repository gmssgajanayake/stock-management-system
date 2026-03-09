<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    // List all orders with filters
    public function index(Request $request)
    {
        $query = Order::with('customer');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        // Filter by customer
        if ($request->customer_id) {
            $query->where('customer_id', $request->customer_id);
        }

        // Optional: Filter by date
        if ($request->from_date) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->to_date) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->latest()->paginate(10);
        $customers = Customer::all();

        return view('orders.index', compact('orders', 'customers'));
    }

    // Show create order form
    public function create()
    {
        $customers = Customer::all();
        $products = Product::all();
        return view('orders.create', compact('customers', 'products'));
    }

    // Store new order
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0', // tax percentage
        ]);

        // Generate order number like SO-00001
        $lastOrder = Order::latest()->first();
        $nextNumber = $lastOrder ? intval(substr($lastOrder->order_number, 3)) + 1 : 1;
        $orderNumber = 'SO-' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Calculate totals
        $subtotal = 0;
        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            $subtotal += $product->price * $item['qty'];
        }

        $discount = $request->discount ?? 0;
        $taxPercent = $request->tax ?? 0;
        $taxAmount = ($subtotal - $discount) * ($taxPercent / 100);
        $grandTotal = $subtotal - $discount + $taxAmount;

        $order = Order::create([
            'order_number' => $orderNumber,
            'customer_id' => $request->customer_id,
            'status' => 'PENDING',
            'subtotal' => $subtotal,
            'discount' => $discount,
            'tax' => $taxAmount,
            'grand_total' => $grandTotal,
        ]);

        foreach ($request->items as $item) {
            $product = Product::find($item['product_id']);
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'qty' => $item['qty'],
                'unit_price' => $product->price,
            ]);
        }

        return redirect()->route('orders.index')->with('success', 'Order created successfully!');
    }


    // Show single order
    public function show(Order $order)
    {
        $order->load('customer', 'items.product');
        return view('orders.show', compact('order'));
    }

    // Update order status
    // Confirm order and reduce stock
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:PENDING,CONFIRMED,CANCELLED,DELIVERED'
        ]);

        // Only PENDING orders can be confirmed
        if ($request->status === 'CONFIRMED' && $order->status === 'PENDING') {
            DB::transaction(function () use ($order) {
                // Check stock for all items
                foreach ($order->items as $item) {
                    if ($item->qty > $item->product->stock) {
                        abort(400, "Insufficient stock for product {$item->product->name}");
                    }
                }

                // Reduce stock
                foreach ($order->items as $item) {
                    $product = $item->product;
                    $product->stock -= $item->qty;
                    $product->save();
                }

                // Update order status
                $order->status = 'CONFIRMED';
                $order->save();
            });
        } else if (in_array($request->status, ['CANCELLED', 'DELIVERED'])) {
            $order->status = $request->status;
            $order->save();
        } else if ($request->status === 'PENDING') {
            // Allow reversion only if order is CONFIRMED? Optional
            $order->status = 'PENDING';
            $order->save();
        }

        return response()->json(['success' => true, 'status' => $order->status]);
    }
}
