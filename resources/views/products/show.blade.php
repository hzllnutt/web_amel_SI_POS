@extends('layouts.app')

@section('title', 'Detail Produk')
@section('page_title', 'Detail Produk Menu')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm overflow-hidden">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-coffee mb-0">
                        <i class="bi bi-info-circle-fill me-2 text-gold"></i>Detail Menu: {{ $product->product_name }}
                    </h6>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali ke Daftar
                    </a>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4 align-items-center">
                        <div class="col-12 col-md-5 text-center">
                            <div class="p-2 border rounded-4 bg-light shadow-sm d-inline-block">
                                <img src="{{ $product->photo_url }}" alt="{{ $product->product_name }}" 
                                     class="rounded-3 img-fluid" style="max-height: 240px; object-fit: cover;">
                            </div>
                        </div>

                        <div class="col-12 col-md-7">
                            <span class="badge bg-cream text-coffee border border-warning border-opacity-50 px-3 py-1 mb-2">
                                <i class="bi bi-tag-fill me-1"></i> {{ $product->category->category_name ?? '-' }}
                            </span>
                            <h3 class="fw-bold text-coffee mb-2">{{ $product->product_name }}</h3>
                            <h4 class="fw-bold text-gold mb-3">{{ $product->formatted_price }}</h4>

                            <div class="mb-3">
                                <label class="text-muted small fw-semibold d-block">Status Stok:</label>
                                <span class="badge {{ $product->stock_badge_class }} px-3 py-2 fs-6 mt-1">
                                    @if($product->product_stock <= 0)
                                        <i class="bi bi-x-circle me-1"></i> SOLD OUT (0 Item)
                                    @elseif($product->product_stock <= 5)
                                        <i class="bi bi-exclamation-triangle me-1"></i> Stok Menipis ({{ $product->product_stock }} Item)
                                    @else
                                        <i class="bi bi-check-circle me-1"></i> Stok Tersedia ({{ $product->product_stock }} Item)
                                    @endif
                                </span>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted small fw-semibold d-block">Status Produk:</label>
                                @if($product->is_active)
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                        <i class="bi bi-eye me-1"></i> Aktif (Ditampilkan di POS)
                                    </span>
                                @else
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">
                                        <i class="bi bi-eye-slash me-1"></i> Nonaktif (Disembunyikan)
                                    </span>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="text-muted small fw-semibold d-block">Deskripsi:</label>
                                <p class="text-secondary mt-1 mb-0">
                                    {{ $product->product_description ?: 'Tidak ada deskripsi untuk produk ini.' }}
                                </p>
                            </div>

                            <div class="d-flex gap-2">
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning text-dark px-3">
                                    <i class="bi bi-pencil-square me-1"></i> Edit Menu
                                </a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-outline-danger btn-delete-confirm" 
                                            data-name="produk '{{ $product->product_name }}'">
                                        <i class="bi bi-trash me-1"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
