<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LowStockProductController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'threshold' => ['sometimes', 'integer', 'min:0', 'max:1000000'],
        ]);

        $threshold = (int) ($data['threshold'] ?? 5);

        return response()->json([
            'threshold' => $threshold,
            'data' => Product::where('stock_quantity', '<', $threshold)
                ->orderBy('stock_quantity')
                ->get(),
        ]);
    }
}