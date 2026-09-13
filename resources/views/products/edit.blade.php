@extends('layouts.app')

@section('title', 'Edit Produk')
@section('page_title', 'Edit Data Menu')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-coffee mb-0">
                        <i class="bi bi-pencil-square me-2 text-gold"></i>Edit Produk: {{ $product->product_name }}
                    </h6>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Kembali
                    </a>
                </div>

                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-8">
                                <label for="product_name" class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" name="product_name" id="product_name" 
                                       class="form-control @error('product_name') is-invalid @enderror" 
                                       value="{{ old('product_name', $product->product_name) }}" required>
                                @error('product_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-4">
                                <label for="category_id" class="form-label fw-semibold">Kategori <span class="text-danger">*</span></label>
                                <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->category_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="product_price" class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">Rp</span>
                                    <input type="number" name="product_price" id="product_price" 
                                           class="form-control @error('product_price') is-invalid @enderror" 
                                           value="{{ old('product_price', (int) $product->product_price) }}" min="0" step="500" required>
                                </div>
                                @error('product_price')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="product_stock" class="form-label fw-semibold">Stok Saat Ini <span class="text-danger">*</span></label>
                                <input type="number" name="product_stock" id="product_stock" 
                                       class="form-control @error('product_stock') is-invalid @enderror" 
                                       value="{{ old('product_stock', $product->product_stock) }}" min="0" required>
                                <div class="form-text small">Admin dapat mengubah stok langsung di sini.</div>
                                @error('product_stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label for="product_description" class="form-label fw-semibold">Deskripsi Menu</label>
                                <textarea name="product_description" id="product_description" rows="3" 
                                          class="form-control @error('product_description') is-invalid @enderror">{{ old('product_description', $product->product_description) }}</textarea>
                                @error('product_description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="product_photo_input" class="form-label fw-semibold">Ganti Foto Produk</label>
                                <input type="file" name="product_photo" id="product_photo_input" 
                                       class="form-control @error('product_photo') is-invalid @enderror" 
                                       accept="image/*">
                                <div class="form-text small">Biarkan kosong jika tidak ingin mengganti foto saat ini.</div>
                                @error('product_photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12 col-md-6 d-flex align-items-center justify-content-center">
                                <div class="text-center p-2 border rounded bg-light" style="width: 140px; height: 140px;">
                                    <img id="product_photo_preview" src="{{ $product->photo_url }}" 
                                         alt="Preview Foto" class="rounded w-100 h-100" style="object-fit: cover;">
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="form-check form-switch mt-2">
                                    <input class="form-check-input" type="checkbox" role="switch" name="is_active" id="is_active" value="1" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold" for="is_active">
                                        Produk Aktif (Tampilkan di Kasir POS)
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer bg-light p-3 d-flex justify-content-end gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Batal</a>
                        <button type="submit" class="btn btn-coffee">
                            <i class="bi bi-check-circle me-1"></i> Perbarui Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/products.js') }}"></script>
@endpush
