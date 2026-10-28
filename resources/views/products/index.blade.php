
@extends('layouts.app')

@section('title', 'Products')

@section('content')
<style>
    .products-pagination {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 12px 16px;
        border-top: 1px solid #dee2e6;
        overflow-x: auto;
    }

    .products-pagination nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        margin: 0;
    }

    .products-pagination .pagination {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex-wrap: wrap;
        gap: 5px;
        margin: 0;
    }

    .products-pagination .page-item {
        display: flex;
        align-items: center;
    }

    .products-pagination .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        min-height: 36px;
        padding: 8px 12px;
        line-height: 1;
        border-radius: 6px;
    }

    .products-pagination .page-link svg {
        display: block;
        width: 16px;
        height: 16px;
        flex-shrink: 0;
    }

    .products-pagination .page-item.disabled .page-link {
        cursor: not-allowed;
    }

    @media (max-width: 576px) {
        .products-pagination {
            justify-content: center;
            padding: 12px 8px;
        }

        .products-pagination nav {
            justify-content: center;
        }

        .products-pagination .pagination {
            gap: 3px;
        }
    }
</style>

<div class="container-fluid px-0">

    {{-- Page Header --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h1 class="h3 mb-0">Products</h1>

        <a href="{{ route('products.create') }}" class="btn btn-primary">
            Add Product
        </a>
    </div>

    {{-- Search --}}
    <form class="row g-2 mb-3" method="GET" action="{{ route('products.index') }}">
        <div class="col-md-5">
            <input
                type="text"
                class="form-control"
                name="search"
                placeholder="Search name or code"
                value="{{ request('search') }}"
                aria-label="Search products by name or code"
            >
        </div>

        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">
                Search
            </button>

            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                Reset
            </a>
        </div>
    </form>

    {{-- Products Table --}}
    <div class="card border-0 shadow-sm">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Code</th>
                        <th>Price</th>
                        <th>Tax %</th>
                        <th>Stock</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>

                            <td>{{ $product->product_code }}</td>

                            <td>
                                {{ number_format((float) $product->unit_price, 2) }}
                            </td>

                            <td>{{ $product->tax_percentage }}%</td>

                            <td>
                                <span class="badge {{ $product->stock_quantity < 5 ? 'text-bg-warning' : 'text-bg-success' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>

                            <td class="text-nowrap">
                                <a
                                    class="btn btn-sm btn-outline-primary"
                                    href="{{ route('products.edit', $product) }}"
                                >
                                    Edit
                                </a>

                                <form
                                    method="POST"
                                    class="d-inline"
                                    action="{{ route('products.destroy', $product) }}"
                                    onsubmit="return confirm('Delete this product?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">
                                No products found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if ($products->hasPages())
            <div class="products-pagination">
                {{ $products->onEachSide(1)->links('pagination::bootstrap-5') }}
            </div>
        @endif

    </div>
</div>
@endsection