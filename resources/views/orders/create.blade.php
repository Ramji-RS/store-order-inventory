
@extends('layouts.app')

@section('title', 'Create Order')

@section('content')
<style>
    .stock-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .stock-available {
        background: #d1e7dd;
        color: #0f5132;
    }

    .stock-low {
        background: #fff3cd;
        color: #664d03;
    }

    .stock-out {
        background: #f8d7da;
        color: #842029;
    }

    .product-row {
        border-bottom: 1px solid #e9ecef;
        padding-top: 18px;
        padding-bottom: 18px;
        transition: background-color 0.2s ease;
    }

    .product-row:last-child {
        border-bottom: none;
    }

    .product-row.is-out-of-stock {
        background: #fff8f8;
    }

    .stock-message {
        font-size: 12px;
        margin-top: 6px;
    }

    .quantity-input:disabled {
        background-color: #e9ecef;
        cursor: not-allowed;
    }
</style>

<h1 class="h3 mb-4">Create Order</h1>

<form id="order-form" method="POST" action="{{ route('orders.store') }}">
    @csrf

    {{-- Customer Information --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <strong>Customer Information</strong>
        </div>

        <div class="card-body row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Customer name</label>

                <input
                    name="customer[name]"
                    class="form-control @error('customer.name') is-invalid @enderror"
                    required
                    value="{{ old('customer.name') }}"
                >

                @error('customer.name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Customer email</label>

                <input
                    type="email"
                    name="customer[email]"
                    class="form-control @error('customer.email') is-invalid @enderror"
                    required
                    value="{{ old('customer.email') }}"
                >

                @error('customer.email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <strong>Order Items</strong>
        </div>

        <div class="card-body">
            <p class="text-secondary small">
                Enter a quantity for the products you want to order.
                Stock colours indicate availability. Prices and totals are calculated by the server.
            </p>

            {{-- Stock Legend --}}
            <div class="d-flex flex-wrap gap-2 mb-3">
                <span class="stock-badge stock-available">● In Stock (10+)</span>
                <span class="stock-badge stock-low">● Low Stock (1–9)</span>
                <span class="stock-badge stock-out">● Out of Stock (0)</span>
            </div>

            @error('items')
                <div class="alert alert-danger">{{ $message }}</div>
            @enderror

            @forelse ($products as $product)
                @php
                    $stock = (int) $product->stock_quantity;

                    if ($stock <= 0) {
                        $stockClass = 'stock-out';
                        $stockLabel = 'Out of Stock';
                        $stockIcon = '●';
                    } elseif ($stock < 10) {
                        $stockClass = 'stock-low';
                        $stockLabel = 'Low Stock';
                        $stockIcon = '●';
                    } else {
                        $stockClass = 'stock-available';
                        $stockLabel = 'In Stock';
                        $stockIcon = '●';
                    }
                @endphp

                <div class="product-row row align-items-center {{ $stock <= 0 ? 'is-out-of-stock' : '' }}">
                    <div class="col-md-6 mb-3 mb-md-0">
                        <strong>{{ $product->name }}</strong>

                        <div class="small text-secondary mt-1">
                            Code: {{ $product->product_code }}
                        </div>

                        <div class="small text-secondary mt-1">
                            Price: {{ number_format((float) $product->unit_price, 2) }}
                            &middot;
                            Tax: {{ $product->tax_percentage }}%
                        </div>

                        <div class="mt-2">
                            <span class="stock-badge {{ $stockClass }}">
                                {{ $stockIcon }} {{ $stockLabel }}: {{ $stock }}
                            </span>
                        </div>

                        @if ($stock <= 0)
                            <div class="stock-message text-danger">
                                This product is unavailable for ordering.
                            </div>
                        @elseif ($stock < 10)
                            <div class="stock-message text-warning-emphasis">
                                Limited stock available. Order carefully.
                            </div>
                        @endif
                    </div>

                    <div class="col-md-3">
                        <label class="form-label">Quantity</label>

                        <input
                            type="hidden"
                            name="items[{{ $loop->index }}][product_id]"
                            value="{{ $product->id }}"
                            @if ($stock <= 0) disabled @endif
                        >

                        <input
                            type="number"
                            class="form-control quantity-input @error('items.' . $loop->index . '.quantity') is-invalid @enderror"
                            name="items[{{ $loop->index }}][quantity]"
                            step="1"
                            min="1"
                            max="{{ $stock }}"
                            placeholder="{{ $stock <= 0 ? 'Out of stock' : 'Enter quantity' }}"
                            value="{{ old('items.' . $loop->index . '.quantity') }}"
                            @if ($stock <= 0) disabled @endif
                        >

                        @error('items.' . $loop->index . '.quantity')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if ($stock > 0)
                            <div class="form-text">Maximum: {{ $stock }}</div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="alert alert-warning mb-0">
                    No products are available. Please add products before creating an order.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Form Actions --}}
    <button
        type="submit"
        class="btn btn-primary btn-lg"
        @if ($products->isEmpty() || $products->every(fn ($product) => $product->stock_quantity <= 0)) disabled @endif
    >
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
        let invalidQuantity = false;

        quantityInputs.forEach(function (input) {
            if (input.disabled) {
                return;
            }

            const quantity = input.value.trim();
            const productRow = input.closest('.product-row');
            const productIdInput = productRow.querySelector(
                'input[type="hidden"][name*="[product_id]"]'
            );

            if (quantity === '') {
                input.disabled = true;

                if (productIdInput) {
                    productIdInput.disabled = true;
                }

                return;
            }

            selectedProducts++;

            const numericQuantity = Number(quantity);
            const maxStock = Number(input.max);

            if (
                !Number.isInteger(numericQuantity) ||
                numericQuantity < 1 ||
                numericQuantity > maxStock
            ) {
                invalidQuantity = true;
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        });

        if (selectedProducts === 0 || invalidQuantity) {
            event.preventDefault();

            // Re-enable skipped inputs if validation fails.
            quantityInputs.forEach(function (input) {
                if (input.max === '0') {
                    return;
                }

                input.disabled = false;

                const productRow = input.closest('.product-row');
                const productIdInput = productRow.querySelector(
                    'input[type="hidden"][name*="[product_id]"]'
                );

                if (productIdInput) {
                    productIdInput.disabled = false;
                }
            });

            if (selectedProducts === 0) {
                alert('Please select at least one product and enter a quantity.');
            } else {
                alert('Please enter a valid quantity within the available stock.');
            }
        }
    });
});
</script>
@endsection