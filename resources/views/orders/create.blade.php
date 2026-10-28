@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<h1 class="h3 mb-4">Create Order</h1>

<form id="order-form" method="POST" action="{{ route('orders.store') }}">
    @csrf

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">Customer information</div>
        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Customer name</label>
                <input
                    name="customer[name]"
                    class="form-control"
                    required
                    value="{{ old('customer.name') }}"
                >
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Customer email</label>
                <input
                    type="email"
                    name="customer[email]"
                    class="form-control"
                    required
                    value="{{ old('customer.email') }}"
                >
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white">Order items</div>

        <div class="card-body">
            <p class="text-secondary">
                Enter a quantity only for the products you want to order.
                Leave other quantities blank. Prices and totals are calculated by the server.
            </p>

            @foreach ($products as $product)
                <div class="product-row row align-items-center border-bottom py-3">
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

                        <input
                            type="hidden"
                            name="items[{{ $loop->index }}][product_id]"
                            value="{{ $product->id }}"
                        >

                        <input
                            type="number"
                            class="form-control quantity-input"
                            name="items[{{ $loop->index }}][quantity]"
                            step="1"
                            min="1"
                            max="{{ $product->stock_quantity }}"
                            placeholder="Leave blank to skip"
                            value="{{ old('items.' . $loop->index . '.quantity') }}"
                            @if ($product->stock_quantity < 1) disabled @endif
                        >
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-lg">
        Confirm Order
    </button>

    <a class="btn btn-outline-secondary btn-lg" href="{{ route('orders.index') }}">
        Cancel
    </a>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('order-form');

    if (!form) {
        return;
    }

    form.addEventListener('submit', function (event) {
        const quantityInputs = form.querySelectorAll('.quantity-input');
        let selectedProducts = 0;

        quantityInputs.forEach(function (input) {
            const quantity = input.value.trim();
            const productRow = input.closest('.product-row');
            const productIdInput = productRow.querySelector(
                'input[type="hidden"][name*="[product_id]"]'
            );

            if (quantity === '') {
                // Ignore this product completely.
                input.disabled = true;

                if (productIdInput) {
                    productIdInput.disabled = true;
                }
            } else {
                selectedProducts++;
            }
        });

        if (selectedProducts === 0) {
            event.preventDefault();

            // Re-enable inputs so the user can correct the form.
            quantityInputs.forEach(function (input) {
                input.disabled = false;

                const productIdInput = input
                    .closest('.product-row')
                    .querySelector('input[type="hidden"][name*="[product_id]"]');

                if (productIdInput) {
                    productIdInput.disabled = false;
                }
            });

            alert('Please select at least one product and enter a quantity of 1 or more.');
        }
    });
});
</script>
@endsection
