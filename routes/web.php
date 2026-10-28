<?php

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WebOrderController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard');

Route::resource('products', ProductController::class);

Route::get('/customers', [CustomerController::class, 'index'])
    ->name('customers.index');

Route::get('/customers/orders', [CustomerController::class, 'orders'])
    ->name('customers.orders');

Route::get('/orders', [WebOrderController::class, 'index'])
    ->name('orders.index');

Route::get('/orders/create', [WebOrderController::class, 'create'])
    ->name('orders.create');

Route::post('/orders', [WebOrderController::class, 'store'])
    ->name('orders.store');

Route::get('/orders/{order}', [WebOrderController::class, 'show'])
    ->name('orders.show');