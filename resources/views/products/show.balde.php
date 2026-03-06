@extends('dashboard')

@section('content-title')
<h1>Product Details</h1>
@endsection

@section('content')
<ul>
    <li>ID: {{ $product->id }}</li>
    <li>Name: {{ $product->name }}</li>
    <li>SKU: {{ $product->sku }}</li>
    <li>Price: {{ $product->price }}</li>
    <li>Stock: {{ $product->stock }}</li>
    <li>Status: {{ $product->is_active ? 'Active' : 'Deactive' }}</li>
    <li>Category: {{ $product->category->name ?? '' }}</li>
</ul>
<a href="{{ route('products.index') }}">Back</a>
@endsection
