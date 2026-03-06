<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

use Illuminate\Support\Str;

class CategoryController extends Controller
{
   public function store(Request $request)
{
    $request->validate([
        'name' => 'required|unique:categories,name'
    ]);

    $category = Category::create([
        'name' => $request->name,
        'slug' => Str::slug($request->name),
    ]);

    return response()->json($category);
}
}
