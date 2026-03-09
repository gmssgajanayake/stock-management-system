@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-4 text-gray-800">Customers</h1>
@endsection

@section('content')

    <div class="mb-6 flex flex-col sm:flex-row justify-between gap-4">
        <button id="createCustomerBtn" class="px-5 py-2.5 bg-green-600 text-white rounded-lg w-full sm:w-auto">
            + Add Customer
        </button>

        <input type="text" id="searchCustomer" placeholder="Search customer..."
            class="border px-4 py-2 rounded-lg w-full sm:w-auto">
    </div>

    <div class="bg-white rounded-lg shadow border border-gray-200 overflow-x-auto">
        <table class="min-w-full">
            <thead class="bg-gray-50 text-left text-xs uppercase text-gray-500">
                <tr>
                    <th class="px-6 py-3 whitespace-nowrap">ID</th>
                    <th class="px-6 py-3 whitespace-nowrap">First Name</th>
                    <th class="px-6 py-3 whitespace-nowrap">Last Name</th>
                    <th class="px-6 py-3 whitespace-nowrap">Phone</th>
                    <th class="px-6 py-3 whitespace-nowrap">Email</th>
                    <th class="px-6 py-3 text-center whitespace-nowrap">Actions</th>
                </tr>
            </thead>

            <tbody id="customerTable"></tbody>
        </table>
    </div>

    <div id="pagination" class="mt-6 flex flex-wrap gap-2 justify-center sm:justify-end"></div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function () {
            loadCustomers()

            $("#createCustomerBtn").click(function () {
                window.location.href = "/customers/create"
            })

            $(document).on("click", ".editBtn", function () {
                let id = $(this).data("id")
                window.location.href = `/customers/${id}/edit`
            })

            $(document).on("click", ".showBtn", function () {
                let id = $(this).data("id")
                window.location.href = `/customers/${id}`
            })

            $(document).on("click", ".deleteBtn", function () {
                let id = $(this).data("id")

                if (confirm("Delete this customer?")) {
                    $.ajax({
                        url: `/customers/${id}`,
                        type: "DELETE",
                        data: { _token: "{{ csrf_token() }}" },
                        success: function () {
                            loadCustomers()
                        }
                    })
                }
            })

            $(document).on("click", ".pageBtn", function () {
                let page = $(this).data("page")
                loadCustomers(page)
            })

            $("#searchCustomer").on("keyup", function () {
                loadCustomers(1)
            })
        })

        function getPerPage() {

            let width = window.innerWidth

            if (width < 640) {
                return 10        // Mobile
            }
            else if (width < 1024) {
                return 15        // Tablet
            }
            else {
                return 6       // Desktop
            }

        }

        function createPagination(data) {
            let pagination = ""

            for (let i = 1; i <= data.last_page; i++) {
                pagination += `
                    <button class="pageBtn px-4 py-2 border rounded" data-page="${i}">
                        ${i}
                    </button>
                    `
            }

            $("#pagination").html(pagination)
        }

        function loadCustomers(page = 1) {
            let search = $("#searchCustomer").val();

            $.get("/customers/list", {
                page: page,
                search: search,
                per_page:getPerPage()
            }, function (res) {
                let rows = ""

                res.data.forEach(c => {
                    // Added whitespace-nowrap to all <td> elements
                    rows += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">${c.id}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${c.first_name}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${c.last_name ?? ''}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${c.phone}</td>
                            <td class="px-6 py-4 whitespace-nowrap">${c.email ?? '-'}</td>

                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <button class="showBtn px-3 py-1.5 text-xs font-medium bg-blue-50 text-blue-600 rounded hover:bg-blue-100 transition-colors" data-id="${c.id}">Show</button>
                                <button class="editBtn px-3 py-1.5 text-xs font-medium bg-amber-50 text-amber-600 rounded hover:bg-amber-100 transition-colors" data-id="${c.id}">Edit</button>
                                <button class="deleteBtn px-3 py-1.5 text-xs font-medium bg-red-50 text-red-600 rounded hover:bg-red-100 transition-colors" data-id="${c.id}">Delete</button>
                            </td>
                        </tr>
                        `
                })

                $("#customerTable").html(rows)

                createPagination(res)
            })
        }
    </script>
@endsection
