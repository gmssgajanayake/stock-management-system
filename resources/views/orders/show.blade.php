@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Order {{ $order->order_number }}</h1>
@endsection

@section('content')
<div class="bg-white rounded-lg shadow border border-gray-200 p-6 max-w-4xl mx-auto">

    <div class="mb-4 grid grid-cols-2 gap-4 sm:grid-cols-4">
        <div><strong>Subtotal:</strong> LKR {{ $order->subtotal }}</div>
        <div><strong>Discount:</strong> LKR {{ $order->discount }}</div>
        <div><strong>Tax:</strong> LKR {{ $order->tax }}</div>
        <div><strong>Grand Total:</strong> LKR {{ $order->grand_total }}</div>
        <div class="sm:col-span-4"><strong>Status:</strong> {{ $order->status }}</div>
    </div>

    <h2 class="font-semibold mb-2">Items</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">Product</th>
                    <th class="px-6 py-3 whitespace-nowrap">Qty</th>
                    <th class="px-6 py-3 whitespace-nowrap">Unit Price</th>
                    <th class="px-6 py-3 whitespace-nowrap">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr class="border-b">
                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->product->name }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $item->qty }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">LKR {{ $item->unit_price }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">LKR {{ $item->qty * $item->unit_price }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>
@endsection
