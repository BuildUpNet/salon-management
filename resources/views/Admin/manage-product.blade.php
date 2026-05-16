@extends('Admin.layouts.main')

@section('title')
    Manage Product
@endsection

@section('content')
    <style>
        mark {
            background-color: #ffdd57;
            color: black;
            padding: 2px 4px;
            border-radius: 3px;
        }
    </style>
    <div class="d-flex flex-column flex-grow-1">

        <!-- Main Content Area -->
        <div class="flex-grow-1 p-4 overflow-auto">
            <div id="productSection">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4>Products</h4>
                    <div class="d-flex gap-2">
                        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addProductModal">Add
                            Product</button>
                        <a href="{{ route('admin.manage') }}" class="btn btn-danger">Back</a>
                    </div>
                </div>

                <form method="GET" action="{{ route('admin.manage-product') }}" class="mb-3 d-flex gap-2">
                    <input type="text" name="search" class="form-control"
                        placeholder="Search by product name, ID, or category..." value="{{ request('search') }}" />
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @php
                    function highlightText($text, $search)
                    {
                        if (!$search) {
                            return $text;
                        }
                        return preg_replace('/(' . preg_quote($search, '/') . ')/i', '<mark>$1</mark>', $text);
                    }
                @endphp
                <!-- Table -->
                <table class="table table-bordered product-table">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Product Image</th>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @foreach ($products as $index => $product)
                            <tr>
                                <td>{{ ($products->currentPage() - 1) * $products->perPage() + $index + 1 }}</td>

                                <td>
                                    @if ($product->product_image)
                                        <img src="{{ asset('uploads/products/' . $product->product_image) }}" width="80"
                                            height="80" alt="Product Image">
                                    @else
                                        <span>No Image</span>
                                    @endif
                                </td>
                                <td>{!! highlightText($product->product_id, request('search')) !!}</td>
                                <td>{!! highlightText($product->product_name, request('search')) !!}</td>
                                <td>{!! highlightText($product->category, request('search')) !!}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->stock }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-warning editProductBtn"
                                        data-id="{{ $product->id }}" data-product_name="{{ $product->product_name }}"
                                        data-category="{{ $product->category }}" data-price="{{ $product->price }}"
                                        data-stock="{{ $product->stock }}" data-image="{{ $product->product_image }}"
                                        data-bs-toggle="modal" data-bs-target="#editProductModal">
                                        Edit
                                    </button>


                                    <form action="{{ route('admin.delete-product', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?')" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                    <tfoot>
                        <tr>
                            <td colspan="8">
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $products->links('pagination::bootstrap-5') }}
                                </div>
                            </td>
                        </tr>
                    </tfoot>

                </table>
            </div>
        </div>

        <!-- Product Modal -->
        <div class="modal fade" id="addProductModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="productModalTitle">Add Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.add-product') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                @include('Admin.manage-product.product-form')
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Save</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="modal fade" id="editProductModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form id="editProductForm" method="POST" enctype="multipart/form-data">

                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Product</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <input type="hidden" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label">Product Name</label>
                                <input type="text" name="product_name" id="edit_product_name" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Product Image</label>
                                <input type="file" name="product_image" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Current Image</label><br>
                                <img id="edit_product_preview" src="" width="100" height="100"
                                    alt="No Image">
                            </div>


                            <div class="mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" id="edit_category" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Price</label>
                                <input type="text" name="price" id="edit_price" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="stock" id="edit_stock" class="form-control">
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button class="btn btn-primary" type="submit">Update</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('editProductForm');

                document.querySelectorAll('.editProductBtn').forEach(button => {
                    button.addEventListener('click', () => {
                        const id = button.dataset.id;
                        const name = button.dataset.product_name;
                        const category = button.dataset.category;
                        const price = button.dataset.price;
                        const stock = button.dataset.stock;
                        const image = button.dataset.image; // get image name

                        // Fill input fields
                        document.getElementById('edit_product_name').value = name;
                        document.getElementById('edit_category').value = category;
                        document.getElementById('edit_price').value = price;
                        document.getElementById('edit_stock').value = stock;

                        // Set form action URL
                        form.action = `/admin/update-product/${id}`;

                        // Set image preview
                        document.getElementById('edit_product_preview').src =
                            `/uploads/products/${image}`;
                    });
                });
            });
        </script>

    @endsection
