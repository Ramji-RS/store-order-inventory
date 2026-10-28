<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $customers = Customer::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', compact('customers'));
    }

    public function orders(Request $request): View
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $customer = Customer::where(
            'email',
            strtolower(trim($data['email']))
        )->firstOrFail();

        $orders = $customer->orders()
            ->with('items.product')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customers.orders', compact('customer', 'orders'));
    }
}