<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of products with filters, search, and pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');
        $categoryId = $request->query('category_id');
        $status = $request->query('status');

        $categories = Category::orderBy('category_name')->get();

        $products = Product::with('category')
            ->when($search, function ($query, $search) {
                return $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($status !== null && $status !== '', function ($query) use ($status) {
                return $query->where('is_active', $status == '1');
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('products.index', compact('products', 'categories', 'search', 'categoryId', 'status'));
    }

    protected function authorizeAdmin(): void
    {
        if (!auth()->check() || !auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengelola master data produk.');
        }
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $this->authorizeAdmin();
        $categories = Category::orderBy('category_name')->get();
        return view('products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $this->authorizeAdmin();
        $validated = $request->validated();

        if ($request->hasFile('product_photo')) {
            $path = $request->file('product_photo')->store('products', 'public');
            $validated['product_photo'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        Product::create($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil ditambahkan.');
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load('category');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $this->authorizeAdmin();
        $categories = Category::orderBy('category_name')->get();
        return view('products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $this->authorizeAdmin();
        $validated = $request->validated();

        if ($request->hasFile('product_photo')) {
            // Delete old photo if exists
            if ($product->product_photo && Storage::disk('public')->exists($product->product_photo)) {
                Storage::disk('public')->delete($product->product_photo);
            }

            $path = $request->file('product_photo')->store('products', 'public');
            $validated['product_photo'] = $path;
        }

        $validated['is_active'] = $request->boolean('is_active');

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil diperbarui.');
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        $this->authorizeAdmin();
        // Check if product is in any order details
        if ($product->orderDetails()->count() > 0) {
            return redirect()->route('products.index')
                ->with('error', 'Produk ini tidak dapat dihapus karena sudah memiliki riwayat transaksi.');
        }

        if ($product->product_photo && Storage::disk('public')->exists($product->product_photo)) {
            Storage::disk('public')->delete($product->product_photo);
        }

        $product->delete();

        return redirect()->route('products.index')
            ->with('success', 'Produk berhasil dihapus.');
    }
}
