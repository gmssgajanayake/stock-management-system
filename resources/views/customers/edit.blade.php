@extends('dashboard')

@section('content-title')
    <h1 class="text-2xl font-bold mb-4 text-gray-800">
        Edit Customer
    </h1>
@endsection

@section('content')

    <div class="bg-white p-6 rounded-lg shadow max-w-xl">

        <form id="editCustomerForm">

            @csrf
            @method('PUT')

            <input type="hidden" name="id" value="{{ $hash }}">

            <div class="mb-4">
                <label>First Name</label>
                <input type="text" name="first_name" value="{{ $customer->first_name }}"
                    class="w-full border px-4 py-2 rounded">
            </div>

            <div class="mb-4">
                <label>Last Name</label>
                <input type="text" name="last_name" value="{{ $customer->last_name }}"
                    class="w-full border px-4 py-2 rounded">
            </div>

            <div class="mb-4">
                <label>Phone</label>
                <input type="text" name="phone" value="{{ $customer->phone }}" class="w-full border px-4 py-2 rounded">
            </div>

            <div class="mb-4">
                <label>Email</label>
                <input type="email" name="email" value="{{ $customer->email }}" class="w-full border px-4 py-2 rounded">
            </div>

            <button type="submit" class="bg-blue-600 text-white px-5 py-2 rounded">
                Update Customer
            </button>

        </form>

    </div>

@endsection

@section('scripts')

    <script>

        $("#editCustomerForm").submit(function (e) {

            e.preventDefault()

            let id = $("input[name=id]").val()

            $.ajax({
                url: `/customers/${id}`,
                type: "PUT",
                data: $(this).serialize(),
                success: function () {

                    alert("Customer updated")

                    window.location.href = "/customers"

                }
            })

        })

    </script>

@endsection
