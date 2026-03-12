<?php

use App\Http\Controllers\BulkUploadController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Public route (accessible without login)
Route::view('/', 'welcome');

// Authentication routes
require __DIR__.'/auth.php';

// All routes that require authentication
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard and profile
    Route::view('dashboard', 'dashboard.index')->name('dashboard');
    Route::view('profile', 'profile')->name('profile');

    // Products
    Route::get('/products/list', [ProductController::class, 'list']);
    Route::put('/products/{hash}/status', [ProductController::class, 'toggleStatus']);
    Route::resource('/products', ProductController::class);

    // Categories
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/customers/list', [CustomerController::class, 'list']);

    // Customers
    Route::resource('/customers', CustomerController::class);

    // Orders
    // Route::resource('/orders', OrderController::class);
    Route::prefix('orders')->group(function () {
        // Bulk order processing
        Route::resource('/bulk-upload', BulkUploadController::class);
        Route::get('/template', [BulkUploadController::class, 'downloadTemplate'])
            ->name('orders.template');
        Route::post('/bulk-upload', [BulkUploadController::class, 'store'])
            ->name('orders.bulk-upload.store');
        Route::post('/bulk-upload/revalidate', [BulkUploadController::class, 'revalidate']);
        Route::post('/bulk-upload/process', [BulkUploadController::class, 'processOrders'])
            ->name('orders.bulk-upload.process');
        Route::get('/bulk-upload/results', [BulkUploadController::class, 'results'])->name('orders.bulk-upload.results');

        // Single order place
        Route::get('/', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

    });

});
