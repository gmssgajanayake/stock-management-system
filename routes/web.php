<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard.index')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__ . '/auth.php';

Route::get('/products/list', [ProductController::class,'list']);
Route::put('/products/{product}/status', [ProductController::class, 'toggleStatus']);
Route::resource('/products', ProductController::class);


Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');

Route::resource('/customers', CustomerController::class);


Route::resource('/orders', OrderController::class);


