<?php

namespace App\Jobs;

use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProcessBulkOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $rows;

    public function __construct(array $rows)
    {
        $this->rows = $rows;
    }

    public function handle()
    {
        $rows = array_map('str_getcsv', Storage::get($this->filePath));
        $header = array_shift($rows);

        $valid = [];
        $invalid = [];

        foreach ($rows as $row) {
            $data = array_combine($header, $row);

            // Split customer name
            $customerParts = explode(' ', trim($data['customer_name']), 2);
            $firstName = $customerParts[0] ?? null;
            $lastName = $customerParts[1] ?? null;

            $validator = Validator::make($data, [
                'customer_name' => [
                    'required',
                    function ($attr, $value, $fail) use ($firstName, $lastName) {
                        if (! Customer::where('first_name', $firstName)
                            ->where('last_name', $lastName)
                            ->exists()) {
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
                $data['tax']='';
                $data['errors'] = $validator->errors()->toArray();
                $invalid[] = $data;
            } else {
                $valid[] = $data;
            }
        }



        // Store results somewhere (database, cache, or file)
        Storage::put('bulk_upload_results/'.$this->filePath.'.json', json_encode([
            'valid' => $valid,
            'invalid' => $invalid,
        ]));
    }
}
