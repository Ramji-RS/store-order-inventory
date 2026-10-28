<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Models\Customer;
use App\Models\Order;
use App\Models\Product;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WebOrderController extends Controller
{
    public function index(): View
    {
        $orders = Order::with('customer')->latest()->paginate(10);

        return view('orders.index', compact('orders'));
    }

    public function create(): View
    {
        return view('orders.create', [
            'products' => Product::where('stock_quantity', '>', 0)
                ->orderBy('name')->get(),
        ]);
    }

    public function store(
        StoreOrderRequest $request,
        OrderService $service
    ): RedirectResponse {
        $order = $service->create($request->validated());

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Order confirmed successfully.');
    }

    public function show(Order $order): View
    {
        $order->load(['customer', 'items.product']);

        return view('orders.show', compact('order'));
    }
}