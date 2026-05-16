<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\add_product;

class ProductDashboardController extends Controller
{
 public function manage_product(Request $request)
{
    $query = add_product::query();

    if ($request->filled('search')) {
        $search = $request->input('search');

        $query->where(function ($q) use ($search) {
            $q->where('product_name', 'like', "%{$search}%")
              ->orWhere('product_id', 'like', "%{$search}%")
              ->orWhere('category', 'like', "%{$search}%");
        });
    }

    $products = $query->paginate(10)->appends($request->only('search'));

    return view('Admin.manage-product', compact('products'));
}
public function add_product(Request $request)
{
    $validator = Validator::make($request->all(), [
        'product_name'   => 'required',
        'category'       => 'required',
        'price'          => 'required',
        'stock'          => 'required|integer',
        'product_image'  => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $uploadPath = public_path('uploads/products');

    if (!file_exists($uploadPath)) {
        mkdir($uploadPath, 0777, true);
    }

    $imageName = null;
    if ($request->hasFile('product_image')) {
        $image = $request->file('product_image');
        $imageName = time() . '.' . $image->getClientOriginalExtension();
        $image->move($uploadPath, $imageName);  // <- this handles it!
    }

    $lastProduct = add_product::orderBy('id', 'desc')->first();
    $nextId = $lastProduct ? ((int)substr($lastProduct->product_id, 4)) + 1 : 1;
    $productId = 'pro-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);

    add_product::create([
        'product_id'     => $productId,
        'product_name'   => $request->product_name,
        'category'       => $request->category,
        'price'          => $request->price,
        'stock'          => $request->stock,
        'product_image'  => $imageName,
    ]);

    return redirect()->route('admin.manage-product')->with('success', 'Product added successfully.');
}

    public function update_product(Request $request, $id)
    {
        $request->validate([
            'product_name'   => 'required|string|max:255',
            'category'       => 'required|string|max:255',
            'price'          => 'required',
            'stock'          => 'required|integer|min:0',
            'product_image'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = add_product::findOrFail($id);

        $uploadPath = public_path('uploads/products');
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        // Handle image upload
        if ($request->hasFile('product_image')) {
            $image = $request->file('product_image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move($uploadPath, $imageName);

            // Delete old image
            if ($product->product_image && file_exists($uploadPath . '/' . $product->product_image)) {
                unlink($uploadPath . '/' . $product->product_image);
            }

            $product->product_image = $imageName;
        }

        $product->update([
            'product_name'   => $request->product_name,
            'category'       => $request->category,
            'price'          => $request->price,
            'stock'          => $request->stock,
            'product_image'  => $product->product_image,
        ]);

        return redirect()->route('admin.manage-product')->with('success', 'Product updated successfully.');
    }

    public function delete_product($id)
    {
        $product = add_product::findOrFail($id);

        $imagePath = public_path('uploads/products/' . $product->product_image);
        if ($product->product_image && file_exists($imagePath)) {
            unlink($imagePath);
        }

        $product->delete();
        return redirect()->route('admin.manage-product')->with('success', 'Product deleted successfully.');
    }
}
