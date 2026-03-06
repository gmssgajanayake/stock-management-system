@extends('dashboard')

@section('content-title')
<h1 class="hidden lg:block text-2xl font-bold mb-4">Products</h1>
<span class="block lg:hidden text-lg font-semibold mb-4">Products</span>
@endsection

@section('content')
<a href="{{ route('products.create') }}" class="btn btn-primary mb-4">Add New Product</a>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>SKU</th>
            <th>Name</th>
            <th>Slug</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Active Status</th>
            <th>Image</th>
            <th>Category</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody id="productTable"></tbody>
</table>

<div id="pagination"></div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    loadProducts();
});

function loadProducts(page = 1) {
    $.get("/products?page=" + page, function (response) {
        let rows = "";
        response.data.forEach(p => {
            rows += `
                <tr>
                    <td>${p.id}</td>
                    <td>${p.sku}</td>
                    <td>${p.name}</td>
                    <td>${p.slug}</td>
                    <td>${p.price}</td>
                    <td>${p.stock}</td>
                    <td>${p.is_active ? 'Active' : 'Deactive'}</td>
                    <td>${p.mainImage ?? ''}</td>
                    <td>${p.category?.name ?? ''}</td>
                    <td>
                        <a href="/products/${p.id}/edit" class="editBtn" data-id="${p.id}">Edit</a>
                        <button class="deleteBtn" data-id="${p.id}">Delete</button>
                    </td>
                </tr>`;
        });
        $("#productTable").html(rows);
        createPagination(response);
    });
}

$(document).on("click", ".deleteBtn", function () {
    let id = $(this).data("id");
    $.ajax({
        url: "/products/" + id,
        type: "DELETE",
        data: { _token: "{{ csrf_token() }}" },
        success: function () { loadProducts(); }
    });
});

function createPagination(data) {
    let pagination = "";
    for (let i = 1; i <= data.last_page; i++) {
        pagination += `<button class="pageBtn" data-page="${i}">${i}</button>`;
    }
    $("#pagination").html(pagination);
}

$(document).on("click", ".pageBtn", function () {
    let page = $(this).data("page");
    loadProducts(page);
});
</script>
@endsection
