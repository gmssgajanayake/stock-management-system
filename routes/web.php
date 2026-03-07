<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

// Public route (accessible without login)
Route::view('/', 'welcome');

// Authentication routes
require __DIR__ . '/auth.php';

// All routes that require authentication
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard and profile
    Route::view('dashboard', 'dashboard.index')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Products
    Route::get('/products/list', [ProductController::class,'list']);
    Route::put('/products/{product}/status', [ProductController::class, 'toggleStatus']);
    Route::resource('/products', ProductController::class);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

    // Customers
    Route::resource('/customers', CustomerController::class);

    // Orders
    Route::resource('/orders', OrderController::class);
});
