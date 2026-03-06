@extends('dashboard')

@section('content-title') {{-- Desktop view --}}
    <h1 class="hidden lg:block text-2xl font-bold mb-4">
        Products
    </h1>
    {{-- Mobile view --}}
    <span class="block lg:hidden text-lg font-semibold mb-4">
        Products
    </span>
@endsection


@section('content') <table>
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
        <tbody id="productTable">

        </tbody>


    </table>
    <div id="pagination"></div>
@endsection


@section('scripts')

    <script>

        $(document).ready(function () {

            loadProducts();

        });

        $(document).on("click", ".pageBtn", function () {

            let page = $(this).data("page");

            loadProducts(page);

        });

        $(document).on("click", ".deleteBtn", function () {
            let id = $(this).data("id");

            $.ajax({
                url: "/products/" + id,
                type: "DELETE",
                data: { _token: "{{ csrf_token() }}" },
                success: function () { loadProducts(); }
            });
        });

        $(document).on("click", ".editBtn", function () {
            let id = $(this).data("id");
            $.get("/products/" + id, function (product) {
                $("#edit_id").val(product.id);
                $("#edit_name").val(product.name);
                $("#edit_price").val(product.price);
            });
        });

        $("#updateProduct").click(function () {
            let id = $("#edit_id").val();
            $.ajax({
                url: "/products/" + id,
                type: "PUT",
                data: {
                    _token: "{{ csrf_token() }}",
                    name: $("#edit_name").val(),
                    price: $("#edit_price").val()
                },
                success: function () { loadProducts(); }
            });
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
                pagination += `
                            <button class="pageBtn" data-page="${i}">
                                ${i}
                            </button>
                            `;
            }

            $("#pagination").html(pagination);
        }


    </script>
@endsection
