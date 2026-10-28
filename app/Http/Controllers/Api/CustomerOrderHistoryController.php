<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerOrderHistoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $customer = Customer::where(
            'email',
            strtolower(trim($data['email']))
        )->first();

        if (! $customer) {
            return response()->json([
                'message' => 'Customer not found.',
            ], 404);
        }

        return response()->json([
            'data' => $customer->orders()
                ->with('items.product')
                ->latest()
                ->get(),
        ]);
    }
}