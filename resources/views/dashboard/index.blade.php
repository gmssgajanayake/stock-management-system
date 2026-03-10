@extends('dashboard')

@section('content-title')
    {{-- Desktop view --}}
    <h1 class="hidden lg:block text-2xl font-bold mb-4 text-gray-800">
        Dashboard
    </h1>
    {{-- Mobile view --}}
    <span class="block lg:hidden text-lg font-semibold mb-4 text-gray-800">
        Dashboard
    </span>
@endsection

@section('content')
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Total Users</h3>
            <p class="mt-2 text-3xl font-bold text-gray-800">1,245</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Revenue</h3>
            <p class="mt-2 text-3xl font-bold text-gray-800">LKR 12,400</p>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100">
            <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Active Sessions</h3>
            <p class="mt-2 text-3xl font-bold text-gray-800">342</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mt-8">

        <div class="lg:col-span-2 p-0 bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-100">
                <h2 class="text-xl font-bold text-gray-800">Recent Tasks</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 text-xs uppercase tracking-wider">
                            <th class="px-6 py-4 font-medium">Task Name</th>
                            <th class="px-6 py-4 font-medium">Status</th>
                            <th class="px-6 py-4 font-medium">Date</th>
                            <th class="px-6 py-4 font-medium text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-100">
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">Update API Routing</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded-full">In Progress</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">Mar 10, 2026</td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">Database Migration</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold text-green-700 bg-green-100 rounded-full">Completed</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">Mar 09, 2026</td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-medium text-gray-900">Setup Docker Environment</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold text-gray-700 bg-gray-100 rounded-full">Pending</span>
                            </td>
                            <td class="px-6 py-4 text-gray-500">Mar 08, 2026</td>
                            <td class="px-6 py-4 text-right">
                                <a href="#" class="text-indigo-600 hover:text-indigo-900 font-medium">Edit</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100">
                <a href="#" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">View all tasks &rarr;</a>
            </div>
        </div>

        <div class="p-6 bg-white rounded-lg shadow-sm border border-gray-100">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Quick Actions</h2>

            <div class="space-y-3">
                <button class="w-full flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Create New Task
                </button>

                <button class="w-full flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    Generate Report
                </button>
            </div>

            <h3 class="text-sm font-bold text-gray-800 mt-8 mb-4 uppercase tracking-wider">System Status</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-600">Server Load</span>
                        <span class="font-medium text-gray-900">45%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-indigo-500 h-2 rounded-full" style="width: 45%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex items-center justify-between text-sm mb-1">
                        <span class="text-gray-600">Storage Capacity</span>
                        <span class="font-medium text-gray-900">80%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-yellow-500 h-2 rounded-full" style="width: 80%"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
