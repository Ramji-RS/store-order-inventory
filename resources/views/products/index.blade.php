
@extends('layouts.app')

@section('title', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Products</h1>
    <a href="{{ route('products.create') }}" class="btn btn-primary">Add Product</a>
</div>

<form class="row g-2 mb-3" method="GET">
    <div class="col-md-5">
        <input class="form-control" name="search" placeholder="Search name or code"
               value="{{ request('search') }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-primary">Search</button>
        <a class="btn btn-outline-secondary" href="{{ route('products.index') }}">Reset</a>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th>Name</th><th>Code</th><th>Price</th>
                    <th>Tax %</th><th>Stock</th><th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->product_code }}</td>
                        <td>{{ number_format((float) $product->unit_price, 2) }}</td>
                        <td>{{ $product->tax_percentage }}%</td>
                        <td>
                            <span class="badge {{ $product->stock_quantity < 5 ? 'text-bg-warning' : 'text-bg-success' }}">
                                {{ $product->stock_quantity }}
                            </span>
                        </td>
                        <td>
                            <a class="btn btn-sm btn-outline-primary"
                               href="{{ route('products.edit', $product) }}">Edit</a>

                            <form method="POST" class="d-inline"
                                  action="{{ route('products.destroy', $product) }}"
                                  onsubmit="return confirm('Delete this product?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center py-4">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $products->links() }}</div>
</div>
@endsection
