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

        <button id="createBtn">Create order</button>
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
                    <td>{{ $order->customer->first_name . ' ' . $order->customer->last_name }}</td>
                    <td>{{ $order->status }}</td>
                    <td>${{ $order->grand_total }}</td>
                    <td class="flex gap-2">

                        <a href="{{ route('orders.show', $order->id) }}" class="text-blue-600">View</a>

                        @if($order->status == 'PENDING')

                            <button class="confirmBtn bg-green-600 text-white px-2 py-1 rounded" data-id="{{ $order->id }}">
                                Confirm
                            </button>

                            <button class="cancelBtn bg-red-600 text-white px-2 py-1 rounded" data-id="{{ $order->id }}">
                                Cancel
                            </button>

                        @elseif($order->status == 'CONFIRMED')

                            <button class="deliverBtn bg-blue-600 text-white px-2 py-1 rounded" data-id="{{ $order->id }}">
                                Deliver
                            </button>

                            <button class="cancelBtn bg-red-600 text-white px-2 py-1 rounded" data-id="{{ $order->id }}">
                                Cancel
                            </button>

                        @endif

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

        $('#createBtn').click(function () {
            window.location.href = 'orders/create';
        });

        function updateStatus(orderId, status) {

            $.ajax({
                url: `/orders/${orderId}/status`,
                type: "PUT",
                data: {
                    status: status,
                    _token: "{{ csrf_token() }}"
                },
                success: function () {
                    location.reload();
                },
                error: function (err) {
                    alert(err.responseJSON.message);
                }
            });

        }

        $(document).on('click', '.confirmBtn', function () {
            updateStatus($(this).data('id'), 'CONFIRMED');
        });

        $(document).on('click', '.cancelBtn', function () {
            updateStatus($(this).data('id'), 'CANCELLED');
        });

        $(document).on('click', '.deliverBtn', function () {
            updateStatus($(this).data('id'), 'DELIVERED');
        });
    </script>
@endsection
