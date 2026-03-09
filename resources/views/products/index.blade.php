{{-- index.blade.php --}}

@extends('dashboard')

@section('content-title')
    <h1 class="hidden lg:block text-2xl font-bold mb-4 text-gray-800">Products</h1>
    <span class="block lg:hidden text-xl font-bold mb-4 text-gray-800">Products</span>
@endsection

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
        <button id="createProductBtn"
            class="w-full sm:w-auto px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-green-500 focus:outline-none">
            + Add New Product
        </button>

        <div class="w-full sm:w-auto relative">
            <input type="text" id="searchProduct" placeholder="Search product by name..."
                class="w-full sm:w-72 border border-gray-300 px-4 py-2.5 rounded-lg shadow-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all">
        </div>
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">SKU</th>
                        <th class="px-6 py-4 sortBtn cursor-pointer hover:text-gray-900 transition-colors" data-sort="name">
                            Name <span class="text-gray-400">⬍</span>
                        </th>
                        <th class="px-6 py-4 sortBtn cursor-pointer hover:text-gray-900 transition-colors"
                            data-sort="price">
                            Price <span class="text-gray-400">⬍</span>
                        </th>
                        <th class="px-6 py-4">Stock</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Main Image</th>
                        <th class="px-6 py-4">Category</th>
                        <th class="px-6 py-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody id="productTable" class="bg-white divide-y divide-gray-200 text-sm text-gray-700">
                </tbody>
            </table>
        </div>
    </div>

    <div id="pagination" class="mt-6 flex flex-wrap gap-2 justify-center sm:justify-end"></div>
@endsection

@section('scripts')
    <script>
        let sortColumn = "";
        let sortDirection = "asc";

        $(document).on("click", ".sortBtn", function () {
            let column = $(this).data("sort");

            if (sortColumn === column) {
                sortDirection = sortDirection === "asc" ? "desc" : "asc";
            } else {
                sortColumn = column;
                sortDirection = "asc";
            }

            loadProducts(1);
        });

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

            $("#createProductBtn").click(function () {
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
                        if (res.status) {
                            btn.text('Active').css('background-color', '#16a34a'); // Tailwind green-600
                        } else {
                            btn.text('Deactive').css('background-color', '#dc2626'); // Tailwind red-600
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

            $.get(`/products/list`, {
                page: page,
                search: search,
                sort: sortColumn,
                direction: sortDirection,
                per_page: getPerPage()
            }, function (response) {

                let rows = "";

                response.data.forEach(p => {
                    rows += `
                            <tr class="hover:bg-gray-50 transition-colors duration-150 ease-in-out">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-medium">${p.sku}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${p.name}</td>
                                <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900">${p.price}</td>
                                <td class="px-6 py-4 whitespace-nowrap">${p.stock}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button class="statusBtn px-3 py-1 rounded-full text-xs text-white font-semibold tracking-wide shadow-sm transition-opacity hover:opacity-90"
                                        data-id="${p.hash_id}"
                                        style="background-color: ${p.is_active ? '#16a34a' : '#dc2626'}">
                                        ${p.is_active ? 'Active' : 'Deactive'}
                                    </button>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    ${p.main_image?.image_path ? `<img src="/storage/${p.main_image.image_path}" class="w-12 h-12 object-cover rounded-md shadow-sm border border-gray-100">` : '<span class="text-gray-400 text-xs italic">No image</span>'}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">${p.category?.name ?? '-'}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        <button class="showBtn px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors" data-id="${p.hash_id}">Show</button>
                                        <button class="editBtn px-3 py-1.5 text-xs font-medium bg-amber-50 text-amber-600 rounded hover:bg-amber-100 transition-colors" data-id="${p.hash_id}">Edit</button>
                                        <button class="deleteBtn px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors" data-id="${p.hash_id}">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        `;
                });

                $("#productTable").html(rows);
                createPagination(response);
            });
        }

        function getPerPage() {

            let width = window.innerWidth

            if (width < 640) {
                return 10        // Mobile
            }
            else if (width < 1024) {
                return 15        // Tablet
            }
            else {
                return 5       // Desktop
            }

        }

        function createPagination(data) {
            let pagination = "";
            for (let i = 1; i <= data.last_page; i++) {
                pagination += `<button class="pageBtn px-4 py-2 text-sm font-medium border border-gray-300 rounded-md shadow-sm bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-colors" data-page="${i}">${i}</button>`;
            }
            $("#pagination").html(pagination);
        }


    </script>
@endsection
