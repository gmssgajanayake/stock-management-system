<x-app-layout>
    <div x-data="{ open: false }" class="flex h-full w-full relative"> <!-- Mobile Overlay -->
        <div x-show="open" x-transition.opacity @click="open = false"
            class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden"> </div> <!-- Sidebar -->
        <div :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed lg:static z-40 inset-y-0 left-0 w-64 bg-gray-800 text-white transform transition-transform duration-300 ease-in-out lg:translate-x-0 flex flex-col">
            <!-- Close button (mobile only) -->
            <div class="lg:hidden flex justify-end p-4"> <button @click="open = false"
                    class="text-white text-xl">✕</button> </div> {{-- seperate here adding MAIN lable--}} <div
                class="mt-8 flex flex-col"> <span
                    class="px-4 text-gray-400 uppercase text-xs font-semibold tracking-wide">Main</span> <a
                    href="{{ route('dashboard') }}" class="w-full hover:bg-slate-500 p-4">Dashboard</a> <a
                    href="{{ route('products.index') }}" class="w-full hover:bg-slate-500 p-4">Products</a> <a
                    href="{{ route('customers.index') }}" class="w-full hover:bg-slate-500 p-4">Customers</a> <a
                    href="{{ route('orders.index') }}" class="w-full hover:bg-slate-500 p-4">Orders</a> </div> {{--
            seperate here adding ADMIN lable--}} <div class="mt-6 flex flex-col"> <span
                    class="px-4 text-gray-400 uppercase text-xs font-semibold tracking-wide">Admin</span> <a
                    href="{{ route('dashboard') }}" class="w-full hover:bg-slate-500 p-4">Users</a> <a
                    href="{{ route('products.index') }}" class="w-full hover:bg-slate-500 p-4">Roles</a> <a
                    href="{{ route('customers.index') }}" class="w-full hover:bg-slate-500 p-4">Permissions</a> </div>
            <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 dark:border-gray-600">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800 dark:text-gray-200"
                            x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                            x-on:profile-updated.window="name = $event.detail.name"></div> {{-- <div
                            class="font-medium text-sm text-gray-500">{{ auth()->user()->email }}</div> --}}
                    </div>
                    <div class="mt-3 space-y-1"> <!-- Profile Link --> <x-responsive-nav-link :href="route('profile')"
                            wire:navigate
                            class="block px-4 py-2  text-gray-300 font-medium transition-colors duration-200 hover:bg-gray-700 hover:text-white">
                            {{ __('Profile') }} </x-responsive-nav-link> <!-- Log Out Link --> <button
                            wire:click="logout" class="w-full text-start"> <x-responsive-nav-link
                                class="block px-4 py-2 text-red-400 font-medium transition-colors duration-200 hover:bg-red-600 hover:text-white">
                                {{ __('Log Out') }} </x-responsive-nav-link> </button> </div>
                </div>
            </div>
        </div> <!-- End Sidebar --> <!-- Content -->
        <div class="flex-1 flex flex-col overflow-hidden"> <!-- Mobile Top Bar -->
            <div class="lg:hidden flex items-center justify-between p-4 bg-white shadow"> <button @click="open = true"
                    class="text-gray-800 text-2xl"> ☰ </button>
                {{-- @if(request()->routeIs('dashboard'))
                <span class="font-semibold">Dashboard</span>
                @endif --}}
                @yield('content-title')
            </div> <!-- Page Content -->
            <div class="flex-1 p-6 overflow-auto">
                <div class="hidden sm:block">
                    {{-- @if(request()->routeIs('dashboard'))
                    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
                    @endif --}}
                    @yield('content-title')
                </div>
                @yield('content')
            </div>
        </div>
    </div>
</x-app-layout>