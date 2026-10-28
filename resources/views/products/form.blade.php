
<div class="mb-3">
    <label class="form-label">Product name</label>
    <input name="name" class="form-control" required maxlength="255"
           value="{{ old('name', $product->name) }}">
</div>

<div class="mb-3">
    <label class="form-label">Product code</label>
    <input name="product_code" class="form-control" required maxlength="255"
           value="{{ old('product_code', $product->product_code) }}">
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Unit price</label>
        <input type="number" name="unit_price" class="form-control"
               step="0.01" min="0" required
               value="{{ old('unit_price', $product->unit_price) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Tax percentage</label>
        <input type="number" name="tax_percentage" class="form-control"
               step="0.01" min="0" max="100" required
               value="{{ old('tax_percentage', $product->tax_percentage ?? 18) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Stock quantity</label>
        <input type="number" name="stock_quantity" class="form-control"
               min="0" step="1" required
               value="{{ old('stock_quantity', $product->stock_quantity ?? 0) }}">
    </div>
</div>

<button class="btn btn-primary">Save product</button>
<a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Cancel</a>
