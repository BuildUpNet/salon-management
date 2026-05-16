<div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" class="form-control @error('product_name') is-invalid @enderror" name="product_name"
        value="{{ old('product_name') }}" required>
    @error('product_name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

    <div class="mb-3">
        <label for="product_image">Product Image</label>
      <input type="file" name="product_image" class="form-control" required>

        @error('product_image')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>

<div class="mb-3">
    <label class="form-label">Category</label>
    <input type="text" class="form-control @error('category') is-invalid @enderror" name="category"
        value="{{ old('category') }}" required>
    @error('category')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Price</label>
    <input type="text" class="form-control @error('price') is-invalid @enderror" name="price"
        value="{{ old('price') }}" step="0.01" required>
    @error('price')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label class="form-label">Stock</label>
    <input type="number" class="form-control @error('stock') is-invalid @enderror" name="stock"
        value="{{ old('stock') }}" required>
    @error('stock')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
