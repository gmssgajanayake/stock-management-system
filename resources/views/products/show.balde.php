@extends('dashboard')

@section('content-title')
    <h1>Product Details</h1>
@endsection

@section('content')
<div>
    <p>SKU: {{ $product->sku }}</p>
    <p>Name: {{ $product->name }}</p>
    <p>Slug: {{ $product->slug }}</p>
    <p>Price: {{ $product->price }}</p>
    <p>Stock: {{ $product->stock }}</p>
    <p>Status: {{ $product->is_active ? 'Active' : 'Deactive' }}</p>
    <p>Category: {{ $product->category->name ?? '' }}</p>
    <div>
        @foreach($product->images as $img)
            <img src="/storage/{{ $img->image_path }}" width="100">
        @endforeach
    </div>
</div>
@endsection
