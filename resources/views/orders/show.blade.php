@extends('dashboard')

@section('content-title')
<h1 class="text-2xl font-bold mb-4">Order {{ $order->order_number }}</h1>
@endsection

@section('content')
<div class="mb-4">
    <strong>Subtotal:</strong> ${{ $order->subtotal }} <br>
    <strong>Discount:</strong> ${{ $order->discount }} <br>
    <strong>Tax:</strong> ${{ $order->tax }} <br>
    <strong>Grand Total:</strong> ${{ $order->grand_total }} <br>
    <strong>Status:</strong> {{ $order->status }}
</div>

<h2 class="font-semibold mb-2">Items</h2>
<table class="min-w-full bg-white border">
    <thead>
        <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Unit Price</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->items as $item)
        <tr class="border-b">
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->qty }}</td>
            <td>${{ $item->unit_price }}</td>
            <td>${{ $item->qty * $item->unit_price }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
