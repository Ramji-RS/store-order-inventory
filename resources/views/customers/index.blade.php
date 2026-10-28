
@extends('layouts.app')

@section('title', 'Customers')

@section('content')
<h1 class="h3 mb-4">Customers</h1>

<form method="GET" class="row g-2 mb-3">
    <div class="col-md-5">
        <input class="form-control" name="search" placeholder="Search name or email"
               value="{{ request('search') }}">
    </div>
    <div class="col-auto">
        <button class="btn btn-outline-primary">Search</button>
        <a class="btn btn-outline-secondary" href="{{ route('customers.index') }}">Reset</a>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Name</th><th>Email</th><th>Orders</th><th>History</th></tr></thead>
            <tbody>
            @forelse ($customers as $customer)
                <tr>
                    <td>{{ $customer->name }}</td>
                    <td>{{ $customer->email }}</td>
                    <td>{{ $customer->orders()->count() }}</td>
                    <td>
                        <a class="btn btn-sm btn-outline-primary"
                           href="{{ route('customers.orders', ['email' => $customer->email]) }}">
                            View history
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center py-4">No customers found.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $customers->links() }}</div>
</div>
@endsection
