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
            </tr>
        </thead>
        <tbody id="productTable">

        </tbody>

    </table>
@endsection


@section('scripts')

    <script>

        $(document).ready(function () {

            loadProducts();

        });

        function loadProducts() {
            $.ajax({

                url: "/products/list",
                type: "GET",

                success: function (products) {

                    let rows = "";

                    products.forEach(function (product) {

                        rows += `
                                <tr>
                                    <td>${product.id}</td>
                                    <td>${product.sku}</td>
                                    <td>${product.name}</td>
                                    <td>${product.slug}</td>
                                    <td>${product.price}</td>
                                    <td>${product.stock}</td>
                                    <td>${product.is_active ? "Active" : "Deactive"}</td>
                                    <td>${product.mainImage ?? ""}</td>
                                    <td>${product.category ? product.category.name : ""}</td>
                                </tr>
                                `;

                    });

                    $("#productTable").html(rows);

                }

            });
        }

    </script>
@endsection
