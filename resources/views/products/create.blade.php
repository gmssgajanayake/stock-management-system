@extends('dashboard')

@section('content-title')
    <h1>Add New Product</h1>
@endsection

@section('content')
<form id="createProductForm" enctype="multipart/form-data">
    @csrf
    <input type="text" name="sku" placeholder="SKU" required>
    <input type="text" name="name" placeholder="Name" required>
    <input type="text" name="slug" placeholder="Slug" required>
    <input type="number" step="0.01" name="price" placeholder="Price" required>
    <input type="number" name="stock" placeholder="Stock" required>
    <select name="category_id" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
        @endforeach
    </select>
    <input type="file" name="images[]" multiple>
    <button type="submit">Create</button>
</form>
@endsection

@section('scripts')
<script>
$("#createProductForm").submit(function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    $.ajax({
        url: "/products",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            alert("Product created!");
            window.location.href = "/products";
        },
        error: function(err) {
            console.log(err);
            alert("Error creating product.");
        }
    });
});
</script>
@endsection
