<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('dashboard', [
            'productCount' => Product::count(),
            'customerCount' => Customer::count(),
            'orderCount' => Order::count(),
            'lowStockCount' => Product::where('stock_quantity', '<', 5)->count(),
            'recentOrders' => Order::with('customer')->latest()->limit(10)->get(),
        ]);
    }
}