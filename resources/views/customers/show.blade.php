@extends('dashboard')

@section('content-title')
<h1 class="text-2xl font-bold mb-4 text-gray-800">
Customer Details
</h1>
@endsection

@section('content')

<div class="bg-white p-6 rounded-lg shadow max-w-xl">

<p><strong>First Name:</strong> {{ $customer->first_name }}</p>

<p><strong>Last Name:</strong> {{ $customer->last_name }}</p>

<p><strong>Phone:</strong> {{ $customer->phone }}</p>

<p><strong>Email:</strong> {{ $customer->email }}</p>

<a href="/customers"
class="mt-4 inline-block bg-gray-500 text-white px-4 py-2 rounded">
Back
</a>

</div>

@endsection
