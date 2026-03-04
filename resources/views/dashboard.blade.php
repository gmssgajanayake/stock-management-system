<x-app-layout>
    <div x-data="{ open: false }" class="flex h-full w-full relative">

        <!-- Mobile Overlay -->
        <div x-show="open" x-transition.opacity @click="open = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden">
        </div>

        <!-- Sidebar -->
        <div :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:static z-40 inset-y-0 left-0 w-64 bg-gray-800 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col">

            <!-- Close button (mobile only) -->
            <div class="lg:hidden flex justify-end p-4">
                <button @click="open = false" class="text-white text-xl">✕</button>
            </div>

            <a href="{{route('dashboard')}}" class="w-full hover:bg-slate-500 p-4">Dashboard</a>
            <a href="{{route('products.index')}}" class="w-full hover:bg-slate-500 p-4">Products</a>
            <a href="{{route('customers.index')}}" class="w-full hover:bg-slate-500 p-4">Customers</a>
            <a href="{{route('orders.index')}}" class="w-full hover:bg-slate-500 p-4">Orders</a>
        </div>

        <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Mobile Top Bar -->
            <div class="lg:hidden flex items-center justify-between p-4 bg-white shadow">
                <button @click="open = true" class="text-gray-800 text-2xl">
                    ☰
                </button>
                <span class="font-semibold">Dashboard</span>
            </div>

            <!-- Page Content -->
            <div class="flex-1 p-6 overflow-auto">
                @if(request()->routeIs('dashboard'))
                    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                @endif

                @yield('content')
            </div>

        </div>
    </div>
</x-app-layout>