@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-4">Orders</h1>
@endsection

@section('content')
    <div class="mb-4 flex gap-4">
        <select id="filterStatus" class="border px-2 py-1 rounded">
            <option value="">All Status</option>
            <option value="PENDING">Pending</option>
            <option value="CONFIRMED">Confirmed</option>
            <option value="CANCELLED">Cancelled</option>
            <option value="DELIVERED">Delivered</option>
        </select>

        <select id="filterCustomer" class="border px-2 py-1 rounded">
            <option value="">All Customers</option>
            @foreach($customers as $c)
                <option value="{{ $c->id }}">{{ $c->first_name . ' ' . $c->last_name }}</option>
            @endforeach
        </select>

        <button id="filterBtn" class="bg-blue-600 text-white px-3 py-1 rounded">Filter</button>
    </div>

    <table class="min-w-full bg-white border">
        <thead>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Status</th>
                <th>Grand Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b">
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ $order->status }}</td>
                    <td>${{ $order->grand_total }}</td>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $orders->links() }}
@endsection

@section('scripts')
    <script>
        $('#filterBtn').click(function () {
            let status = $('#filterStatus').val();
            let customer = $('#filterCustomer').val();
            let url = new URL(window.location.href);
            if (status) url.searchParams.set('status', status);
            else url.searchParams.delete('status');
            if (customer) url.searchParams.set('customer_id', customer);
            else url.searchParams.delete('customer_id');
            window.location.href = url.toString();
        });
    </script>
@endsection
