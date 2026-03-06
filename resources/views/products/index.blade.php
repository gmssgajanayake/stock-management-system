@extends('dashboard')

@section('content-title')
    <h1 class="hidden lg:block text-2xl font-bold mb-4">Products</h1>
    <span class="block lg:hidden text-lg font-semibold mb-4">Products</span>
@endsection

@section('content')
   <button id="createProductBtn" class="mb-4 px-4 py-2 bg-green-500 text-white rounded">
    Add New Product
</button>

    <div class="mb-4 flex gap-2">
        <input
            type="text"
            id="searchProduct"
            placeholder="Search product by name..."
            class="border px-3 py-2 rounded w-64"
        >
    </div>

    <table class="table-auto w-full border">
        <thead>
            <tr>
                <th>Id</th>
                <th>SKU</th>
                <th>Name</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Main Image</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="productTable"></tbody>
    </table>

    <div id="pagination" class="mt-4"></div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    loadProducts();

    // Pagination
    $(document).on("click", ".pageBtn", function () {
        let page = $(this).data("page");
        loadProducts(page);
    });

    // Delete
    $(document).on("click", ".deleteBtn", function () {
        let id = $(this).data("id");
        if (confirm("Are you sure to delete this product?")) {
            $.ajax({
                url: `/products/${id}`,
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function () { loadProducts(); }
            });
        }
    });

    // Edit
    $(document).on("click", ".editBtn", function () {
        let id = $(this).data("id");
        window.location.href = `/products/${id}/edit`;
    });

    // Show
    $(document).on("click", ".showBtn", function () {
        let id = $(this).data("id");
        window.location.href = `/products/${id}`;
    });

     $("#createProductBtn").click(function() {
        window.location.href = "/products/create"; // navigate to create page
    });

    // Toggle Active Status
    $(document).on("click", ".statusBtn", function () {
        let btn = $(this);
        let id = btn.data("id");
        $.ajax({
            url: `/products/${id}/status`,
            type: "PUT",
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                if(res.status) {
                    btn.text('Active').css('background-color', 'green');
                } else {
                    btn.text('Deactive').css('background-color', 'red');
                }
            }
        });
    });
});

// Search while typing
$("#searchProduct").on("keyup", function () {
    loadProducts(1);
});

function loadProducts(page = 1) {

    let search = $("#searchProduct").val();

    $.get(`/products/list?page=${page}&search=${search}`, function (response) {

        let rows = "";

        response.data.forEach(p => {
            rows += `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.sku}</td>
                    <td>${p.name}</td>
                    <td>${p.price}</td>
                    <td>${p.stock}</td>
                    <td>
                        <button class="statusBtn px-2 py-1 rounded text-white font-semibold"
                            data-id="${p.id}"
                            style="background-color: ${p.is_active ? 'green' : 'red'}">
                            ${p.is_active ? 'Active' : 'Deactive'}
                        </button>
                    </td>
                    <td>${p.main_image?.image_path ? `<img src="/storage/${p.main_image.image_path}" width="50">` : ''}</td>
                    <td>${p.category?.name ?? ''}</td>
                    <td>
                        <button class="showBtn" data-id="${p.id}">Show</button>
                        <button class="editBtn" data-id="${p.id}">Edit</button>
                        <button class="deleteBtn" data-id="${p.id}">Delete</button>
                    </td>
                </tr>
            `;
        });

        $("#productTable").html(rows);
        createPagination(response);
    });
}

function createPagination(data) {
    let pagination = "";
    for (let i = 1; i <= data.last_page; i++) {
        pagination += `<button class="pageBtn" data-page="${i}">${i}</button>`;
    }
    $("#pagination").html(pagination);
}
</script>
@endsection
