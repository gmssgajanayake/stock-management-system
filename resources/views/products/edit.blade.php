{{-- edit.blade.php --}}

@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Edit Product: <span class="text-gray-500 font-medium">{{ $product->name }}</span></h1>
@endsection

@section('content')
    <div class="bg-white rounded-lg shadow border border-gray-200 p-6 md:p-8 max-w-5xl mx-auto">
        <form id="editProductForm" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')
            <input type="hidden" name="id" value="{{ $product->id }}">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="sku" value="{{ $product->sku }}" required
                        class="w-full border border-gray-300 bg-gray-50 px-4 py-2.5 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name</label>
                    <input type="text" name="name" value="{{ $product->name }}" required
                        class="w-full border border-gray-300 bg-gray-50 px-4 py-2.5 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Slug</label>
                    <input type="text" name="slug" value="{{ $product->slug }}" required
                        class="w-full border border-gray-300 bg-gray-50 px-4 py-2.5 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="categorySelect" name="category_id" required
                        class="w-full border border-gray-300 bg-gray-50 px-4 py-2.5 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all cursor-pointer">
                        <option value="" disabled>Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                        <option value="new" class="font-bold text-blue-600">-- Add New Category --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ $product->price }}" required
                        class="w-full border border-gray-300 bg-gray-50 px-4 py-2.5 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                    <input type="number" name="stock" value="{{ $product->stock }}" required
                        class="w-full border border-gray-300 bg-gray-50 px-4 py-2.5 rounded-lg shadow-sm focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
                </div>

            </div>

            <hr class="border-gray-200">

          <div>
    <label class="block text-sm font-medium text-gray-700 mb-2">Upload New Images</label>

    <label for="file-upload" class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-blue-500 bg-gray-50 hover:bg-blue-50 transition-colors cursor-pointer group">
        <div class="space-y-1 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-blue-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600 justify-center">
                <span class="relative font-medium text-blue-600 group-hover:text-blue-500">
                    Click to browse files
                </span>
                <input id="file-upload" type="file" name="images[]" multiple class="sr-only">
            </div>
            <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
        </div>
    </label>
    <div id="file-preview-text" class="mt-2 text-sm text-green-600 font-medium hidden"></div>
</div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Existing Images (Choose Main)</label>
                @if($product->images->count() > 0)
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
                        @foreach($product->images as $img)
                            <label class="relative flex flex-col items-center p-3 border rounded-xl cursor-pointer hover:bg-gray-50 transition-colors {{ $img->is_main ? 'border-blue-500 bg-blue-50 ring-1 ring-blue-500' : 'border-gray-200 bg-white' }}">
                                <img src="/storage/{{ $img->image_path }}" class="h-24 w-full object-cover rounded shadow-sm mb-3">
                                <div class="flex items-center gap-2">
                                    <input type="radio" name="main_image_id" value="{{ $img->id }}" {{ $img->is_main ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="text-sm font-medium text-gray-700">Set Main</span>
                                </div>
                            </label>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-gray-500 italic bg-gray-50 p-4 rounded-lg border border-gray-200">No images uploaded for this product yet.</p>
                @endif
            </div>

            <div class="pt-4 flex justify-end gap-3">
                <a href="/products" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-gray-200 focus:outline-none">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
@endsection

@section('scripts')
    <script>

        // Show selected file names for better UX
$("#file-upload").change(function() {
    let fileCount = this.files.length;
    let textDisplay = $("#file-preview-text");

    if (fileCount > 0) {
        let fileNames = Array.from(this.files).map(f => f.name).join(', ');
        textDisplay.text(`${fileCount} file(s) selected: ${fileNames}`).removeClass('hidden');
    } else {
        textDisplay.addClass('hidden');
    }
});

$("#editProductForm").submit(function (e) {
    e.preventDefault();
    let id = $("input[name=id]").val();
    let formData = new FormData(this);

    let submitBtn = $(this).find('button[type="submit"]');
    let originalText = submitBtn.text();
    submitBtn.text('Saving...').prop('disabled', true).addClass('opacity-70 cursor-not-allowed');

    $.ajax({
        url: `/products/${id}`,
        type: "POST", // Keep as POST. Laravel's @method('PUT') inside the form handles the spoofing.
        data: formData,
        processData: false, // Required for files
        contentType: false, // Required for files
        // Removed the X-HTTP-Method-Override header to prevent PHP from dropping the $_FILES array
        success: function (res) {
            alert("Product updated successfully!");
            window.location.href = "/products";
        },
        error: function (err) {
            console.error(err);
            alert("Error updating product. Please check the console.");
            submitBtn.text(originalText).prop('disabled', false).removeClass('opacity-70 cursor-not-allowed');
        }
    });
});
        // Handle "Add New Category"
        $("#categorySelect").change(function() {
            if ($(this).val() === "new") {
                let newCat = prompt("Enter new category name:");
                if (newCat) {
                    $.post("{{ route('categories.store') }}", {
                        name: newCat,
                        _token: "{{ csrf_token() }}"
                    }, function(res) {
                        // Insert before the "Add New Category" option
                        $(`<option value="${res.id}" selected>${res.name}</option>`).insertBefore("#categorySelect option[value='new']");
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

            // Change button to loading state
            let submitBtn = $(this).find('button[type="submit"]');
            let originalText = submitBtn.text();
            submitBtn.text('Saving...').prop('disabled', true).addClass('opacity-70 cursor-not-allowed');

            $.ajax({
                url: `/products/${id}`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                headers: { 'X-HTTP-Method-Override': 'PUT' },
                success: function (res) {
                    alert("Product updated successfully!");
                    window.location.href = "/products";
                },
                error: function (err) {
                    console.error(err);
                    alert("Error updating product. Please check the console.");
                    submitBtn.text(originalText).prop('disabled', false).removeClass('opacity-70 cursor-not-allowed');
                }
            });
        });
    </script>
@endsection
