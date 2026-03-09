<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;

use Vinkla\Hashids\Facades\Hashids;

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
        $query = Product::with(['category', 'mainImage']);

        // Search
        if ($request->search) {
            $query->whereRaw('LOWER(name) LIKE ?', ['%' . strtolower($request->search) . '%']);
        }

        // Sorting
        if ($request->sort) {
            $query->orderBy($request->sort, $request->direction ?? 'asc');
        }

        // Filters
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        $perPage = $request->per_page ?? 6;

        $products = $query->paginate($perPage);

        // Add hash_id to each product
        $products->getCollection()->transform(function ($product) {
            $product->hash_id = Hashids::encode($product->id);
            return $product;
        });

        return response()->json($products);
    }

    public function edit($hash)
    {
        $decoded = Hashids::decode($hash);

        if (empty($decoded)) {
            abort(404); // hash invalid
        }

        $id = $decoded[0];
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
    public function show($hash)
    {
        $decoded = Hashids::decode($hash);

        if (empty($decoded)) {
            abort(404); // hash invalid
        }

        $id = $decoded[0];

        $product = Product::with(['mainImage', 'images', 'category'])->findOrFail($id);
        return view('products.show', compact('product'));
    }
    // Update product
    public function update(Request $request, $hash)
    {

        $decoded = Hashids::decode($hash);

        if (empty($decoded)) {
            abort(404); // hash invalid
        }

        $id = $decoded[0];

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
    public function destroy($hash)
    {
        $decoded = Hashids::decode($hash);

        if (empty($decoded)) {
            abort(404); // hash invalid
        }

        $id = $decoded[0];

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

    public function toggleStatus(Product $product)
    {
        $product->is_active = !$product->is_active; // flip status
        $product->save();

        return response()->json(['success' => true, 'status' => $product->is_active]);
    }
}
