<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all category IDs
        $categoryIds = Category::pluck('id')->toArray();

        // Generate 50 products and assign random categories
        Product::factory()->count(50)->create([
            'category_id' => fn() => fake()->randomElement($categoryIds),
        ]);
    }
}
