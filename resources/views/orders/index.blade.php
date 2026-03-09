@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Orders</h1>
@endsection

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between gap-4">
        <div class="flex gap-2 flex-wrap">
            <select id="filterStatus" class="border px-3 py-2 rounded-lg">
                <option value="">All Status</option>
                <option value="PENDING">Pending</option>
                <option value="CONFIRMED">Confirmed</option>
                <option value="CANCELLED">Cancelled</option>
                <option value="DELIVERED">Delivered</option>
            </select>

            <select id="filterCustomer" class="border px-3 py-2 rounded-lg">
                <option value="">All Customers</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->first_name . ' ' . $c->last_name }}</option>
                @endforeach
            </select>

            <button id="filterBtn" class="bg-blue-600 text-white px-4 py-2 rounded-lg">Filter</button>
        </div>

        <button id="createBtn" class="px-5 py-2.5 bg-green-600 text-white rounded-lg w-full sm:w-auto">
            + Create Order
        </button>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">Order #</th>
                    <th class="px-6 py-3 whitespace-nowrap">Customer</th>
                    <th class="px-6 py-3 whitespace-nowrap">Status</th>
                    <th class="px-6 py-3 whitespace-nowrap">Grand Total</th>
                    <th class="px-6 py-3 text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                    <tr class="border-b">
                        <td class="px-6 py-4 whitespace-nowrap">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $order->customer->first_name . ' ' . $order->customer->last_name }}</td>
                       <td class="px-6 py-4 whitespace-nowrap">
                            @if($order->status == 'PENDING')
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                            @elseif($order->status == 'CONFIRMED')
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Confirmed</span>
                            @elseif($order->status == 'CANCELLED')
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Cancelled</span>
                            @elseif($order->status == 'DELIVERED')
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Delivered</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">LKR {{ $order->grand_total }}</td>
                        <td class="px-6 py-4 text-center whitespace-nowrap flex gap-2 justify-center">
                            <a href="{{ route('orders.show', $order->id) }}"
                                class="px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors">View</a>

                            @if($order->status == 'PENDING')
                                <button
                                    class="confirmBtn px-3 py-1.5 text-xs font-medium bg-green-50 text-green-600 rounded hover:bg-green-100 transition-colors"
                                    data-id="{{ $order->id }}">Confirm</button>
                                <button
                                    class="cancelBtn px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors"
                                    data-id="{{ $order->id }}">Cancel</button>
                            @elseif($order->status == 'CONFIRMED')
                                <button
                                    class="deliverBtn px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors"
                                    data-id="{{ $order->id }}">Deliver</button>
                                <button
                                    class="cancelBtn px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors"
                                    data-id="{{ $order->id }}">Cancel</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
@endsection

@section('scripts')
    <script>
        $('#filterBtn').click(function () {
            let status = $('#filterStatus').val();
            let customer = $('#filterCustomer').val();
            let url = new URL(window.location.href);
            if (status) url.searchParams.set('status', status); else url.searchParams.delete('status');
            if (customer) url.searchParams.set('customer_id', customer); else url.searchParams.delete('customer_id');
            window.location.href = url.toString();
        });

        $('#createBtn').click(function () {
            window.location.href = 'orders/create';
        });

        function updateStatus(orderId, status) {
            $.ajax({
                url: `/orders/${orderId}/status`,
                type: "PUT",
                data: { _token: "{{ csrf_token() }}", status: status },
                success: function () { location.reload(); },
                error: function (err) { alert(err.responseJSON.message); }
            });
        }

        $(document).on('click', '.confirmBtn', function () { updateStatus($(this).data('id'), 'CONFIRMED'); });
        $(document).on('click', '.cancelBtn', function () { updateStatus($(this).data('id'), 'CANCELLED'); });
        $(document).on('click', '.deliverBtn', function () { updateStatus($(this).data('id'), 'DELIVERED'); });
    </script>
@endsection
