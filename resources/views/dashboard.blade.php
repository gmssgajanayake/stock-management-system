<x-app-layout>
    <div class="flex min-h-screen"> <!-- Sidebar -->
        <div class="w-64 bg-gray-800 text-white  flex flex-col">
            <a href="" class="w-full hover:bg-slate-500 p-4">Products</a>
            <a href="" class="w-full  hover:bg-slate-500 p-4">Customers</a>
            <a href="" class="w-full  hover:bg-slate-500 p-4">Oders</a>
        </div>
        <!-- Content -->
        <div class="flex-1 p-6">
            @yield('content')
        </div>
    </div>
</x-app-layout>
