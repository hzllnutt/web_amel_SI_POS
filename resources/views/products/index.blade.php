@extends('layouts.app')

@section('title', 'Produk')
@section('page_title', 'Manajemen Produk Menu')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold text-coffee mb-1">Catalogue Product & Menu</h5>
                {{-- <p class="text-muted small mb-0">Kelola item minuman, makanan, harga, serta ketersediaan stok.</p> --}}
            </div>
            @if(auth()->check() && auth()->user()->isAdmin())
            <a href="{{ route('products.create') }}" class="btn btn-coffee d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>Add Product</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('products.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-4">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-start-0"
                               placeholder="Cari nama produk..." value="{{ $search }}">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <select name="category_id" class="form-select bg-light">
                        <option value="">-- Semua Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $categoryId == $cat->id ? 'selected' : '' }}>
                                {{ $cat->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-6 col-md-3">
                    <select name="status" class="form-select bg-light">
                        <option value="">-- Semua Status --</option>
                        <option value="1" {{ $status === '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ $status === '0' ? 'selected' : '' }}>Non-Aktif</option>
                    </select>
                </div>

                <div class="col-12 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-coffee w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if($search || $categoryId || $status !== null)
                        <a href="{{ route('products.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th style="width: 80px;">Foto</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 160px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $index => $product)
                    <tr>
                        <td class="fw-bold text-muted">{{ $products->firstItem() + $index }}</td>
                        <td>
                            <img src="{{ $product->photo_url }}" alt="{{ $product->product_name }}"
                                 class="rounded border" style="width: 56px; height: 56px; object-fit: cover;">
                        </td>
                        <td>
                            <div class="fw-bold text-dark">{{ $product->product_name }}</div>
                            @if($product->product_description)
                                <div class="text-muted small text-truncate" style="max-width: 250px;">
                                    {{ $product->product_description }}
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-light text-dark border">
                                {{ $product->category->category_name ?? '-' }}
                            </span>
                        </td>
                        <td class="fw-bold text-coffee">{{ $product->formatted_price }}</td>
                        <td>
                            <span class="badge {{ $product->stock_badge_class }} px-2 py-1">
                                @if($product->product_stock <= 0)
                                    <i class="bi bi-x-circle me-1"></i> Habis (0)
                                @elseif($product->product_stock <= 5)
                                    <i class="bi bi-exclamation-triangle me-1"></i> Sisa {{ $product->product_stock }}
                                @else
                                    <i class="bi bi-check2-circle me-1"></i> {{ $product->product_stock }} item
                                @endif
                            </span>
                        </td>
                        <td>
                            @if($product->is_active)
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    Aktif
                                </span>
                            @else
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">
                                    Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            @if(auth()->check() && auth()->user()->isAdmin())
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-sm btn-outline-warning text-dark" title="Edit">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-confirm"
                                        data-name="produk '{{ $product->product_name }}'"
                                        title="Hapus Produk">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-cup-hot fs-1 d-block mb-2"></i>
                            Tidak ada data produk ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
