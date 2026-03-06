@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Product Details</h1>
@endsection

@section('content')
<div class="bg-white shadow-sm border border-gray-200 rounded-xl p-6 md:p-10 max-w-6xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

        <div class="flex flex-col space-y-4">
            <div class="relative w-full overflow-hidden rounded-xl shadow-sm border border-gray-200 bg-gray-50">
                @if($product->mainImage)
                    <img src="/storage/{{ $product->mainImage->image_path }}"
                         class="w-full h-80 md:h-[28rem] object-cover hover:scale-105 transition-transform duration-500"
                         alt="{{ $product->name }}">
                @else
                    <div class="w-full h-80 md:h-[28rem] flex flex-col items-center justify-center text-gray-400">
                        <svg class="w-16 h-16 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <span class="text-sm font-medium">No Main Image Available</span>
                    </div>
                @endif
            </div>

            @if($product->images && $product->images->count() > 1)
                <div class="flex gap-3 overflow-x-auto py-2 scrollbar-hide">
                    @foreach($product->images as $img)
                        <div class="relative shrink-0 w-20 h-20 md:w-24 md:h-24 rounded-lg overflow-hidden border-2 {{ $img->is_main ? 'border-blue-500 ring-2 ring-blue-200' : 'border-gray-200 hover:border-gray-300' }} transition-all">
                            <img src="/storage/{{ $img->image_path }}"
                                 class="w-full h-full object-cover">
                            @if($img->is_main)
                                <div class="absolute bottom-0 inset-x-0 bg-blue-500 text-white text-[10px] font-bold text-center py-0.5">MAIN</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="flex flex-col h-full">
            <div class="mb-6">
                <div class="flex items-start justify-between gap-4 mb-2">
                    <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">{{ $product->name }}</h2>
                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold tracking-wide uppercase shadow-sm mt-1 {{ $product->is_active ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-red-100 text-red-700 border border-red-200' }}">
                        {{ $product->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
                <p class="text-3xl font-bold text-blue-600">LKR {{ number_format($product->price, 2) }}</p>
            </div>

            <hr class="border-gray-100 mb-6">

            <div class="grid grid-cols-2 gap-y-6 gap-x-2 mb-8 flex-grow">
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">SKU</dt>
                    <dd class="text-base font-semibold text-gray-900 bg-gray-50 inline-block px-3 py-1 rounded-md border border-gray-200">{{ $product->sku }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Category</dt>
                    <dd class="text-base font-medium text-gray-900">{{ $product->category->name ?? 'Uncategorized' }}</dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Stock Level</dt>
                    <dd class="flex items-center gap-2">
                        <span class="text-base font-bold {{ $product->stock > 10 ? 'text-green-600' : ($product->stock > 0 ? 'text-amber-500' : 'text-red-600') }}">
                            {{ $product->stock }}
                        </span>
                        <span class="text-sm text-gray-500">units available</span>
                    </dd>
                </div>

                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Total Images</dt>
                    <dd class="text-base font-medium text-gray-900">{{ $product->images ? $product->images->count() : 0 }} uploaded</dd>
                </div>
            </div>

            <div class="pt-6 border-t border-gray-100 flex items-center gap-3 mt-auto">
                <a href="/products" class="flex-1 text-center px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-gray-200 focus:outline-none">
                    &larr; Back to Products
                </a>
                <a href="/products/{{ $product->id }}/edit" class="flex-1 text-center px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    Edit Product
                </a>
            </div>
        </div>

    </div>
</div>
@endsection
