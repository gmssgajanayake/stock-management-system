@extends('dashboard')

@section('content-title')
<h1>Edit Product</h1>
@endsection

@section('content')
<form id="editProductForm">
    @csrf
    @method('PUT')
    <input type="hidden" id="id" value="{{ $product->id }}">

    <label>Name:</label>
    <input type="text" id="name" value="{{ $product->name }}" required>

    <label>Price:</label>
    <input type="number" id="price" value="{{ $product->price }}">

    <button type="submit">Update Product</button>
</form>
@endsection

@section('scripts')
<script>
$("#editProductForm").submit(function(e){
    e.preventDefault();
    let id = $("#id").val();
    $.ajax({
        url: "/products/" + id,
        type: "PUT",
        data: {
            _token: "{{ csrf_token() }}",
            name: $("#name").val(),
            price: $("#price").val()
        },
        success: function(response){
            window.location.href = "/products";
        }
    });
});
</script>
@endsection
