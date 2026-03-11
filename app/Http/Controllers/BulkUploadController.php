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

        $path = $request->file('csv_file')->getRealPath();

        $rows = array_map('str_getcsv', file($path));

        $header = array_shift($rows);

        $valid = [];
        $invalid = [];

        foreach ($rows as $row) {

            // Avoid empty file
            if (empty($rows)) {
                return back()->withErrors(['csv_file' => 'CSV is empty!']);
            }

            $data = array_combine($header, $row);

            $customerParts = explode(' ', $data['customer_name'], 2);
            $data['first_name'] = $customerParts[0] ?? null;
            $data['last_name'] = $customerParts[1] ?? null;

            $validator = Validator::make($data, [
                'first_name' => 'required|exists:customers,first_name',
                'last_name' => 'required|exists:customers,last_name',
                'sku' => 'required|exists:products,sku',
                'qty' => 'required|integer|min:1',
                'discount' => 'required|numeric|min:0',
                'tax' => 'required|numeric|min:0|max:100',
            ]);

            if ($validator->fails()) {

                $data['errors'] = $validator->errors();
                $invalid[] = $data;
            } else {
                $valid[] = $data;
            }
        }

        dd($valid, $invalid); // now shows all rows after processing

        return view('orders.bulk-review', compact('valid', 'invalid'));
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
}
