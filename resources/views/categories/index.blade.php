@extends('layouts.app')

@section('title', 'Kategori')
@section('page_title', 'Manajemen Kategori Produk')

@section('content')
<div class="container-fluid p-0">
    <!-- Header & Action Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold text-coffee mb-1">Daftar Kategori Menu</h5>
                <p class="text-muted small mb-0">Kelola kelompok menu minuman dan makanan el'sCoffe.</p>
            </div>
            <button type="button" class="btn btn-coffee d-inline-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                <i class="bi bi-plus-circle"></i>
                <span>Tambah Kategori</span>
            </button>
        </div>
    </div>

    <!-- Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3">
            <form action="{{ route('categories.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" 
                               placeholder="Cari nama kategori..." value="{{ $search }}">
                        @if($search)
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-coffee">
                        <i class="bi bi-filter me-1"></i> Filter
                    </button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 70px;">No</th>
                        <th>Nama Kategori</th>
                        <th>Jumlah Produk</th>
                        <th>Created At</th>
                        <th class="text-end" style="width: 150px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td class="fw-bold text-muted">{{ $categories->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark d-flex align-items-center gap-2">
                                <span class="badge bg-cream text-coffee border border-warning border-opacity-50 px-2 py-1">
                                    <i class="bi bi-tag-fill me-1"></i> {{ $category->category_name }}
                                </span>
                            </div>
                        </td>
                        <td>
                            <span class="badge {{ $category->products_count > 0 ? 'bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25' : 'bg-secondary bg-opacity-10 text-secondary' }} px-2 py-1">
                                {{ $category->products_count }} Produk
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $category->created_at ? $category->created_at->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="text-end">
                            <button type="button" class="btn btn-sm btn-outline-warning text-dark me-1" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#editCategoryModal" 
                                    data-id="{{ $category->id }}" 
                                    data-name="{{ $category->category_name }}"
                                    title="Edit Kategori">
                                <i class="bi bi-pencil-square"></i>
                            </button>
                            
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-confirm" 
                                        data-name="kategori '{{ $category->category_name }}'"
                                        title="Hapus Kategori">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5 text-muted">
                            <i class="bi bi-tags fs-1 d-block mb-2"></i>
                            Tidak ada kategori ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $categories->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Tambah Kategori -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-coffee text-white">
                <h5 class="modal-title fw-bold" id="createCategoryModalLabel">
                    <i class="bi bi-tag-fill me-2 text-warning"></i>Tambah Kategori Baru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="category_name" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" id="category_name" class="form-control" 
                               placeholder="Contoh: Signature Coffee" required autofocus>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-coffee">
                        <i class="bi bi-check-circle me-1"></i> Simpan Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-coffee text-white">
                <h5 class="modal-title fw-bold" id="editCategoryModalLabel">
                    <i class="bi bi-pencil-square me-2 text-warning"></i>Edit Kategori
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editCategoryForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label for="edit_category_name" class="form-label fw-semibold">Nama Kategori <span class="text-danger">*</span></label>
                        <input type="text" name="category_name" id="edit_category_name" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-coffee">
                        <i class="bi bi-check-circle me-1"></i> Perbarui Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const editModal = document.getElementById('editCategoryModal');
    if (editModal) {
        editModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const name = button.getAttribute('data-name');

            const form = document.getElementById('editCategoryForm');
            form.action = `/categories/${id}`;

            document.getElementById('edit_category_name').value = name;
        });
    }
});
</script>
@endpush
