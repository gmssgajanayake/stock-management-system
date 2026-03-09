@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Create Order</h1>
@endsection

@section('content')
    <div class="bg-white shadow-sm border border-gray-200 rounded-xl p-6 max-w-4xl mx-auto">

        <form id="orderForm" action="{{ route('orders.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block mb-1 font-semibold text-gray-700">Customer</label>
                <select name="customer_id" id="customerSelect"
                    class="select2 w-full border border-gray-300 px-3 py-2 rounded-lg">

                    <option value="">Select Customer</option>

                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">
                            {{ $customer->first_name . ' ' . $customer->last_name }}
                        </option>
                    @endforeach

                </select>
            </div>

            <div id="itemsWrapper" class="space-y-4">
                <div class="itemRow flex gap-4 items-end">
                    <div class="flex-1">
                        <label class="block mb-1 text-gray-700">Product</label>
                        <select name="items[0][product_id]"
                            class="productSelect select2 w-full border px-3 py-2 rounded-lg">

                            <option value="">Select Product</option>

                            @foreach($products as $product)
                                <option value="{{ $product->id }}" data-price="{{ $product->price }}"
                                    data-stock="{{ $product->stock }}">
                                    {{ $product->name }} (Stock: {{ $product->stock }})
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="w-24">
                        <label class="block mb-1 text-gray-700">Qty</label>
                        <input type="number" name="items[0][qty]" class="qtyInput w-full border px-3 py-2 rounded-lg"
                            min="1" value="1">
                    </div>
                    <button type="button" class="removeItemBtn px-3 py-2 bg-red-500 text-white rounded">Remove</button>
                </div>
            </div>

            <button type="button" id="addItemBtn" class="mt-4 px-4 py-2 bg-green-600 text-white rounded">+ Add Item</button>

            <div class="mt-6 space-y-2">
                <label class="block text-gray-700 font-semibold">Discount (Flat):</label>
                <input type="number" name="discount" class="border px-3 py-2 rounded-lg w-32" value="0">
            </div>

            <div class="mt-2 space-y-2">
                <label class="block text-gray-700 font-semibold">Tax (%):</label>
                <input type="number" name="tax" class="border px-3 py-2 rounded-lg w-32" value="0">
            </div>

            <button type="submit" class="mt-6 px-6 py-2 bg-blue-600 text-white rounded-lg">Create Order</button>
        </form>
    </div>
@endsection

@section('scripts')
    <script>
        let itemIndex = 1;

        $('#addItemBtn').click(function () {
            let newItem = $('.itemRow').first().clone();

            newItem.find('select').val('');
            newItem.find('input').val(1);
            newItem.find('select').attr('name', `items[${itemIndex}][product_id]`);
            newItem.find('.qtyInput').attr('name', `items[${itemIndex}][qty]`);
            $('#itemsWrapper').append(newItem);

            itemIndex++;
        });

        $(document).on('click', '.removeItemBtn', function () {
            if ($('.itemRow').length > 1) $(this).closest('.itemRow').remove();
        });
    </script>
@endsection
