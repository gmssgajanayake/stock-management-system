@extends('dashboard')

@section('content-title')
<h1 class="text-2xl font-bold mb-4 text-gray-800">Add Customer</h1>
@endsection

@section('content')

<div class="bg-white p-6 rounded-lg shadow max-w-xl">

<form id="createCustomerForm">

@csrf

<div class="mb-4">
<label>First Name</label>
<input type="text" name="first_name" required
class="w-full border px-4 py-2 rounded">
</div>

<div class="mb-4">
<label>Last Name</label>
<input type="text" name="last_name"
class="w-full border px-4 py-2 rounded">
</div>

<div class="mb-4">
<label>Phone</label>
<input type="text" name="phone" required
class="w-full border px-4 py-2 rounded">
</div>

<div class="mb-4">
<label>Email</label>
<input type="email" name="email"
class="w-full border px-4 py-2 rounded">
</div>

<button type="submit"
class="bg-blue-600 text-white px-5 py-2 rounded">
Create Customer
</button>

</form>

</div>

@endsection


@section('scripts')

<script>

$("#createCustomerForm").submit(function(e){

e.preventDefault()

$.post("/customers",$(this).serialize(),function(){

alert("Customer created")

window.location.href="/customers"

})

})

</script>

@endsection
