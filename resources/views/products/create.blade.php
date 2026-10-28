
@extends('layouts.app')

@section('title', 'Add Product')

@section('content')
<h1 class="h3 mb-4">Add Product</h1>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('products.store') }}">
            @csrf
            @include('products.form')
        </form>
    </div>
</div>
@endsection
