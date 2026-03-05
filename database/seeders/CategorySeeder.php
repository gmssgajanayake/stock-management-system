<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('categories')->truncate();


        $categories = [
            'Stationery',
            'Books',
            'Printing Supplies',
            'Electronics',
            'Office Furniture',
        ];

        foreach ($categories as $cat) {
            Category::create([
                'name' => $cat,
                'slug' => \Str::slug($cat), // generates SEO-friendly slug
            ]);
        }
    }
}
