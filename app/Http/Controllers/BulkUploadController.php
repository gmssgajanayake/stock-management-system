<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BulkUploadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('orders.bulk-upload');
    }

    /**  * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt',
        ]);

        try {
            $path = $request->file('csv_file')->getRealPath();

            $rows = array_map('str_getcsv', file($path));

            // Check if CSV is empty
            if (empty($rows)) {
                return back()->withErrors(['csv_file' => 'CSV is empty!']);
            }

            $header = array_shift($rows);

            $valid = [];
            $invalid = [];

            foreach ($rows as $row) {
                $data = array_combine($header, $row);

                $customerParts = explode(' ', trim($data['customer_name']), 2);
                $firstName = $customerParts[0] ?? null;
                $lastName = $customerParts[1] ?? null;

                $validator = Validator::make($data, [
                    'customer_name' => [
                        'required',
                        function ($attribute, $value, $fail) use ($firstName, $lastName) {

                            $exists = \App\Models\Customer::where('first_name', $firstName)
                                ->where('last_name', $lastName)
                                ->exists();

                            if (! $exists) {
                                $fail('Customer not found');
                            }
                        },
                    ],
                    'sku' => 'required|exists:products,sku',
                    'qty' => 'required|integer|min:1',
                    'discount' => 'required|numeric|min:0',
                    'tax' => 'required|numeric|min:0|max:100',
                ]);

                if ($validator->fails()) {
                    $data['errors'] = $validator->errors()->toArray();
                    $invalid[] = $data;
                } else {
                    $valid[] = $data;
                }
            }

            return response()->json([
                'valid' => $valid,
                'invalid' => $invalid,
            ]);

        } catch (Exception $e) {
            // Catch any unexpected error and return back with message
            return response()->json([
                'error' => 'An error occurred while processing the CSV: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function downloadTemplate()
    {
        $fileName = 'orders_template.csv';

        $headers = [
            'customer_name',
            'sku',
            'qty',
            'discount',
            'tax',
        ];

        $handle = fopen('php://temp', 'r+');

        fputcsv($handle, $headers);

        rewind($handle);

        return response(stream_get_contents($handle), 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$fileName",
        ]);
    }

    public function revalidate(Request $request)
    {
        $rows = $request->rows;

        $valid = [];
        $invalid = [];

        foreach ($rows as $data) {

            $validator = Validator::make($data, [
                'customer_name' => 'required',
                'sku' => 'required|exists:products,sku',
                'qty' => 'required|integer|min:1',
                'discount' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0|max:100',
            ]);

            if ($validator->fails()) {
                $data['tax']='';
                $data['errors'] = $validator->errors()->toArray();
                $invalid[] = $data;
            } else {
                $valid[] = $data;
            }
        }

        return response()->json([
            'valid' => $valid,
            'invalid' => $invalid,
        ]);
    }
}
