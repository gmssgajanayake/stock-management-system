@extends('dashboard') @section('content-title') {{-- Desktop view --}}
    <h1 class="hidden lg:block text-2xl font-bold mb-4">
        Customers
    </h1> {{-- Mobile view --}}
    <span class="block lg:hidden text-lg font-semibold mb-4">
        Customers
    </span>
@endsection @section('content')
    <h1>customers</h1>
@endsection