
@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<h1 class="h3 mb-4">Create Order</h1>

<form method="POST" action="{{ route('orders.store') }}">
    @csrf

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">Customer information</div>
        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Customer name</label>
                <input name="customer[name]" class="form-control" required
                       value="{{ old('customer.name') }}">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Customer email</label>
                <input type="email" name="customer[email]" class="form-control" required
                       value="{{ old('customer.email') }}">
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">Order items</div>
        <div class="card-body">
            <p class="text-secondary">
                Enter a quantity for each product. Prices and totals are calculated by the server.
            </p>

            @foreach ($products as $product)
                <div class="row align-items-center border-bottom py-3">
                    <div class="col-md-6">
                        <strong>{{ $product->name }}</strong>
                        <div class="small text-secondary">
                            {{ $product->product_code }} ·
                            {{ number_format((float) $product->unit_price, 2) }} each ·
                            Tax {{ $product->tax_percentage }}% ·
                            Stock {{ $product->stock_quantity }}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Quantity</label>
                        <input type="hidden" name="items[{{ $loop->index }}][product_id]"
                               value="{{ $product->id }}">
                        <input type="number" class="form-control"
                               name="items[{{ $loop->index }}][quantity]"
                               min="1" max="{{ $product->stock_quantity }}"
                               placeholder="0">
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button class="btn btn-primary btn-lg">Confirm Order</button>
    <a class="btn btn-outline-secondary btn-lg" href="{{ route('orders.index') }}">Cancel</a>
</form>
@endsection
