<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerController extends Controller
{
    // Display customers page
    public function index()
    {
        return view('customers.index');
    }

    // AJAX list
    public function list(Request $request)
    {
        $query = Customer::query();

        // Search
        if ($request->search) {

            $search = strtolower($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(first_name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(last_name) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(phone) LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER(email) LIKE ?', ["%{$search}%"]);
            });

        }

        $perPage = $request->per_page ?? 6;

        $customers = $query->paginate($perPage);

        return response()->json($customers);
    }

    // Show create form
    public function create()
    {
        return view('customers.create');
    }

    // Store new customer
    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:customers,email'
        ]);

        $customer = Customer::create($request->only([
            'first_name',
            'last_name',
            'phone',
            'email'
        ]));

        return response()->json($customer);
    }

    // Show single customer
    public function show($id)
    {
        $customer = Customer::findOrFail($id);

        return view('customers.show', compact('customer'));
    }

    // Edit form
    public function edit($id)
    {
        $customer = Customer::findOrFail($id);

        return view('customers.edit', compact('customer'));
    }

    // Update customer
    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|unique:customers,email,' . $id
        ]);

        $customer->update($request->only([
            'first_name',
            'last_name',
            'phone',
            'email'
        ]));

        return response()->json($customer);
    }

    // Delete customer
    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);

        $customer->delete();

        return response()->json(['success' => true]);
    }
}
