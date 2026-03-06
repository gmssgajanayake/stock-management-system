@extends('dashboard')

@section('content-title')
<h1>Add New Product</h1>
@endsection

@section('content')
<form id="createProductForm">
    @csrf
    <label>Name:</label>
    <input type="text" name="name" id="name" required>

    <label>SKU:</label>
    <input type="text" name="sku" id="sku">

    <label>Price:</label>
    <input type="number" name="price" id="price">

    <label>Stock:</label>
    <input type="number" name="stock" id="stock">

    <button type="submit">Create Product</button>
</form>
@endsection

@section('scripts')
<script>
$("#createProductForm").submit(function(e){
    e.preventDefault();
    $.post("/products", $(this).serialize(), function(response){
        window.location.href = "/products"; // redirect to index after creation
    });
});
</script>
@endsection
