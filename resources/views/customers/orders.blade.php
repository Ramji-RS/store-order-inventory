
@extends('layouts.app')

@section('title', 'Customer Order History')

@section('content')
<h1 class="h3">Order History</h1>
<p class="text-secondary">{{ $customer->name }} · {{ $customer->email }}</p>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Order</th><th>Subtotal</th><th>Tax</th><th>Total</th><th>Date</th></tr></thead>
            <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a></td>
                    <td>{{ number_format((float) $order->subtotal, 2) }}</td>
                    <td>{{ number_format((float) $order->tax_total, 2) }}</td>
                    <td>{{ number_format((float) $order->grand_total, 2) }}</td>
                    <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center py-4">No orders yet.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $orders->links() }}</div>
</div>
@endsection
