@extends('dashboard')

@section('content-title')
    <h1>Edit Product</h1>
@endsection

@section('content')
<form id="editProductForm" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    <input type="hidden" name="id" value="{{ $product->id }}">
    <input type="text" name="sku" value="{{ $product->sku }}" required>
    <input type="text" name="name" value="{{ $product->name }}" required>
    <input type="text" name="slug" value="{{ $product->slug }}" required>
    <input type="number" step="0.01" name="price" value="{{ $product->price }}" required>
    <input type="number" name="stock" value="{{ $product->stock }}" required>
    <select name="category_id" required>
        @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
        @endforeach
    </select>
    <input type="file" name="images[]" multiple>
    <div>
        @foreach($product->images as $img)
            <img src="/storage/{{ $img->image_path }}" width="50">
        @endforeach
    </div>
    <button type="submit">Update</button>
</form>
@endsection

@section('scripts')
<script>
$("#editProductForm").submit(function(e) {
    e.preventDefault();
    let id = $("input[name=id]").val();
    let formData = new FormData(this);
    $.ajax({
        url: `/products/${id}`,
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        headers: { 'X-HTTP-Method-Override': 'PUT' },
        success: function(res) {
            alert("Product updated!");
            window.location.href = "/products";
        },
        error: function(err) {
            console.log(err);
            alert("Error updating product.");
        }
    });
});
</script>
@endsection
