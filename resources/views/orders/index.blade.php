
@extends('layouts.app')

@section('title', 'Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Orders</h1>
    <a class="btn btn-primary" href="{{ route('orders.create') }}">Create Order</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
            @forelse ($orders as $order)
                <tr>
                    <td><a href="{{ route('orders.show', $order) }}">#{{ $order->id }}</a></td>
                    <td>{{ $order->customer->name }}<div class="small text-secondary">{{ $order->customer->email }}</div></td>
                    <td>{{ number_format((float) $order->grand_total, 2) }}</td>
                    <td>{{ ucfirst($order->status) }}</td>
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
