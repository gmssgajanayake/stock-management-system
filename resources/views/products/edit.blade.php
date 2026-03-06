@extends('dashboard')

@section('content-title')
    <h1>Edit Product</h1>
@endsection

@section('content')
    <form id="editProductForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="id" value="{{ $product->id }}">

        <div class="mb-2">
            <label>SKU</label>
            <input type="text" name="sku" value="{{ $product->sku }}" required>
        </div>
        <div class="mb-2">
            <label>Name</label>
            <input type="text" name="name" value="{{ $product->name }}" required>
        </div>
        <div class="mb-2">
            <label>Slug</label>
            <input type="text" name="slug" value="{{ $product->slug }}" required>
        </div>
        <div class="mb-2">
            <label>Price</label>
            <input type="number" step="0.01" name="price" value="{{ $product->price }}" required>
        </div>
        <div class="mb-2">
            <label>Stock</label>
            <input type="number" name="stock" value="{{ $product->stock }}" required>
        </div>
        <div class="mb-2">
            <label>Category</label>
            <select id="categorySelect" name="category_id" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
                <option value="new">-- Add New Category --</option>
            </select>
        </div>

        <div class="mb-2">
            <label>Upload New Images</label>
            <input type="file" name="images[]" multiple>
        </div>

        <div class="mb-4">
            <label>Existing Images (choose main)</label>
            <div class="flex gap-4">
                @foreach($product->images as $img)
                    <div class="flex flex-col items-center">
                        <img src="/storage/{{ $img->image_path }}" width="100" class="rounded border">
                        <label>
                            <input type="radio" name="main_image_id" value="{{ $img->id }}" {{ $img->is_main ? 'checked' : '' }}>
                            Main
                        </label>
                    </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded">Update</button>
    </form>
@endsection

@section('scripts')
    <script>

        // Handle "Add New Category"
        $("#categorySelect").change(function() {
            if ($(this).val() === "new") {
                let newCat = prompt("Enter new category name:");
                if (newCat) {
                    $.post("{{ route('categories.store') }}", {
                        name: newCat,
                        _token: "{{ csrf_token() }}"
                    }, function(res) {
                        $("#categorySelect").append(`<option value="${res.id}" selected>${res.name}</option>`);
                    }).fail(function() {
                        alert("Failed to create category or it already exists.");
                        $("#categorySelect").val(''); // reset selection
                    });
                } else {
                    $(this).val(''); // reset selection if no name entered
                }
            }
        });

        $("#editProductForm").submit(function (e) {
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
                success: function (res) {
                    alert("Product updated!");
                    window.location.href = "/products";
                },
                error: function (err) {
                    console.error(err);
                    alert("Error updating product.");
                }
            });
        });
    </script>
@endsection
