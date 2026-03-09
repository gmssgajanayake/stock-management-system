@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-4 text-gray-800">
        {{ isset($order) ? 'Edit Order' : 'Create Order' }}
    </h1>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6 max-w-4xl mx-auto">

        <form id="orderForm" action="{{ isset($order) ? route('orders.update', $order->id) : route('orders.store') }}"
            method="POST">
            @csrf
            @if(isset($order))
                @method('PUT')
            @endif

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-700">Customer</label>
                <select name="customer_id" required class="w-full border px-4 py-2 rounded">
                    <option value="">Select Customer</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}" {{ isset($order) && $customer->id == $order->customer_id ? 'selected' : '' }}>
                            {{ $customer->first_name . ' ' . $customer->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div id="itemsWrapper" class="space-y-4">
                @if(isset($order))
                    @foreach($order->items as $i => $item)
                        <div class="itemRow flex gap-4 items-end">
                            <div class="flex-1">
                                <label class="block mb-1 text-gray-700">Product</label>
                                <select name="items[{{ $i }}][product_id]" class="w-full border px-4 py-2 rounded">
                                    <option value="">Select Product</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}" {{ $product->id == $item->product_id ? 'selected' : '' }}>
                                            {{ $product->name }} (Stock: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="w-24">
                                <label class="block mb-1 text-gray-700">Qty</label>
                                <input type="number" name="items[{{ $i }}][qty]" class="w-full border px-4 py-2 rounded"
                                    value="{{ $item->qty }}" min="1">
                            </div>
                            <button type="button" class="removeItemBtn px-3 py-2 bg-red-500 text-white rounded">Remove</button>
                        </div>
                    @endforeach
                @else
                    <div class="itemRow flex gap-4 items-end">
                        <div class="flex-1">
                            <label class="block mb-1 text-gray-700">Product</label>
                            <select name="items[0][product_id]" class="w-full border px-4 py-2 rounded">
                                <option value="">Select Product</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }} (Stock: {{ $product->stock }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="w-24">
                            <label class="block mb-1 text-gray-700">Qty</label>
                            <input type="number" name="items[0][qty]" class="w-full border px-4 py-2 rounded" value="1" min="1">
                        </div>
                        <button type="button" class="removeItemBtn px-3 py-2 bg-red-500 text-white rounded">Remove</button>
                    </div>
                @endif
            </div>

            <button type="button" id="addItemBtn" class="mt-4 px-4 py-2 bg-green-600 text-white rounded">+ Add Item</button>

            <div class="mt-6 flex gap-6">
                <div>
                    <label class="block text-gray-700 font-semibold">Discount</label>
                    <input type="number" name="discount" class="border px-4 py-2 rounded w-32"
                        value="{{ $order->discount ?? 0 }}">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold">Tax (%)</label>
                    <input type="number" name="tax" class="border px-4 py-2 rounded w-32" value="{{ $order->tax ?? 0 }}">
                </div>
            </div>

            <button type="submit" class="mt-6 px-6 py-2 bg-blue-600 text-white rounded">
                {{ isset($order) ? 'Update Order' : 'Create Order' }}
            </button>
        </form>

    </div>
@endsection

@section('scripts')
    <script>
        let itemIndex = {{ isset($order) ? $order->items->count() : 1 }};

        $('#addItemBtn').click(function () {
            let newItem = $('.itemRow').first().clone();
            newItem.find('select').val('');
            newItem.find('input').val(1);
            newItem.find('select').attr('name', `items[${itemIndex}][product_id]`);
            newItem.find('input').attr('name', `items[${itemIndex}][qty]`);
            $('#itemsWrapper').append(newItem);
            itemIndex++;
        });

        $(document).on('click', '.removeItemBtn', function () {
            if ($('.itemRow').length > 1) $(this).closest('.itemRow').remove();
        });
    </script>
@endsection
