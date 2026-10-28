<?php

use App\Http\Controllers\Api\CustomerOrderHistoryController;
use App\Http\Controllers\Api\LowStockProductController;
use App\Http\Controllers\Api\OrderController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::post('/orders', [OrderController::class, 'store']);

    Route::get('/customers/orders', [
        CustomerOrderHistoryController::class, 'index',
    ]);

    Route::get('/products/low-stock', [
        LowStockProductController::class, 'index',
    ]);
});