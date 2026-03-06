<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;

class ProductController extends Controller
{
    // Display the products page (blade)
    public function index()
    {
        return view('products.index'); // blade handles AJAX data
    }

    // Fetch products for AJAX
    public function list(Request $request)
    {
        $products = Product::with(['category', 'mainImage'])->paginate(10);
        return response()->json($products);
    }

    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    // Store new product with images
    public function store(Request $request)
    {
        $request->validate([
            'sku' => 'required|unique:products,sku',
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = Product::create($request->only(['sku', 'name', 'slug', 'price', 'stock', 'category_id', 'is_active']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_main' => $key === 0 ? true : false
                ]);
            }
        }

        return response()->json($product);
    }

    // Show single product
    public function show($id)
    {
        $product = Product::with('images', 'category')->findOrFail($id);
        return response()->json($product);
    }

    // Update product
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $request->validate([
            'sku' => 'required|unique:products,sku,' . $id,
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $id,
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_image_id' => 'nullable|exists:product_images,id', // optional: let user select main image
        ]);

        $product->update($request->only([
            'sku',
            'name',
            'slug',
            'price',
            'stock',
            'category_id',
            'is_active'
        ]));

        // Handle uploaded images
        if ($request->hasFile('images')) {

            // If you want the first uploaded image to replace main image:
            foreach ($request->file('images') as $key => $image) {
                $path = $image->store('products', 'public');

                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $path,
                    'is_main' => false, // default false
                ]);
            }
        }

        // Handle main image selection (optional)
        if ($request->filled('main_image_id')) {
            // Reset previous main image
            $product->mainImage()->update(['is_main' => false]);

            // Set new main image
            $mainImage = ProductImage::find($request->main_image_id);
            if ($mainImage) {
                $mainImage->is_main = true;
                $mainImage->save();
            }
        }

        return response()->json($product);
    }

    // Delete product
    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        foreach ($product->images as $image) {
            \Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }

        $product->delete();

        return response()->json(['success' => true]);
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }
}
