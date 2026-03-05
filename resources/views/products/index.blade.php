@extends('dashboard') @section('content-title') {{-- Desktop view --}}
    <h1 class="hidden lg:block text-2xl font-bold mb-4">
        Product
    </h1> {{-- Mobile view --}}
    <span class="block lg:hidden text-lg font-semibold mb-4">
        Product
    </span>
@endsection @section('content')
    <h1>product</h1>
@endsection