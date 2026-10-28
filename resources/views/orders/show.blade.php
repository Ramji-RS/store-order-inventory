
@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
<h1 class="h3 mb-1">Order #{{ $order->id }}</h1>
<p class="text-secondary">{{ $order->customer->name }} · {{ $order->customer->email }}</p>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Product</th><th>Qty</th><th>Unit Price</th><th>Tax</th><th>Total</th></tr></thead>
            <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format((float) $item->unit_price, 2) }}</td>
                    <td>{{ number_format((float) $item->tax_amount, 2) }}</td>
                    <td>{{ number_format((float) $item->line_total, 2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-body text-end">
        <p>Subtotal: {{ number_format((float) $order->subtotal, 2) }}</p>
        <p>Tax: {{ number_format((float) $order->tax_total, 2) }}</p>
        <h2 class="h4">Grand total: {{ number_format((float) $order->grand_total, 2) }}</h2>
    </div>
</div>
@endsection
