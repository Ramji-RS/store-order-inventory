
@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<h1 class="h3 mb-4">Edit Product</h1>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('products.update', $product) }}">
            @csrf
            @method('PUT')
            @include('products.form')
        </form>
    </div>
</div>
@endsection
