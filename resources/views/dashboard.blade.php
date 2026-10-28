
@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<h1 class="h3 mb-4">Dashboard</h1>

<div class="row g-3 mb-4">
    @foreach ([
        'Products' => $productCount,
        'Customers' => $customerCount,
        'Orders' => $orderCount,
        'Low Stock' => $lowStockCount,
    ] as $label => $value)
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-secondary">{{ $label }}</div>
                    <div class="display-6">{{ $value }}</div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">Recent orders</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Date</th></tr></thead>
            <tbody>
            @forelse ($recentOrders as $order)
                <tr>
                    <td><a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a></td>
                    <td>{{ $order->customer->name }}</td>
                    <td>{{ number_format((float) $order->grand_total, 2) }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4">No orders yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
