@extends('layouts.app') // Menggunakan layout utama aplikasi.

@section('title', 'Manajemen User') // Judul halaman pada tab browser.
@section('page_title', 'Manajemen Pengguna & Hak Akses') // Judul yang tampil di halaman.

@section('content')
<div class="container-fluid p-0">

    <!-- Card Header (Bagian atas halaman) -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">

            <!-- Judul dan deskripsi halaman -->
            <div>
                <h5 class="fw-bold text-coffee mb-1">Create New User</h5>
                {{-- Tempat untuk menambahkan deskripsi jika diperlukan --}}
            </div>

            <!-- Tombol untuk menuju halaman tambah user -->
            <a href="{{ route('users.create') }}" class="btn btn-coffee d-inline-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill"></i>
                <span>Add User</span>
            </a>

        </div>
    </div>

    <!-- Card Tabel Data User -->
    <div class="card border-0 shadow-sm">

        <!-- Bagian pencarian user -->
        <div class="card-header bg-white border-bottom p-3">

            <!-- Form pencarian menggunakan metode GET -->
            <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">

                <!-- Input pencarian nama atau email -->
                <div class="col-12 col-md-5">
                    <div class="input-group">

                        <!-- Icon pencarian -->
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>

                        <!-- Input kata kunci pencarian -->
                        <input type="text"
                               name="search"
                               class="form-control bg-light border-start-0"
                               placeholder="Cari nama atau email pengguna..."
                               value="{{ $search }}">

                        <!-- Tombol reset akan muncul jika ada kata pencarian -->
                        @if($search)
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                Reset
                            </a>
                        @endif

                    </div>
                </div>

                <!-- Tombol untuk menjalankan pencarian -->
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-coffee">
                        <i class="bi bi-filter me-1"></i> Search
                    </button>
                </div>

            </form>
        </div>

        <!-- Tabel daftar pengguna -->
        <div class="table-responsive">
            <table class="table table-hover table-modern align-middle mb-0">

                <!-- Judul kolom tabel -->
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Registered since</th>
                        <th class="text-end" style="width: 140px;">Action</th>
                    </tr>
                </thead>

                <!-- Isi tabel -->
                <tbody>

                    <!-- Menampilkan data user, jika ada -->
                    @forelse($users as $index => $user)

                    <tr>

                        <!-- Nomor urut data -->
                        <td class="fw-bold text-muted">
                            {{ $users->firstItem() + $index }}
                        </td>

                        <!-- Nama user -->
                        <td>
                            <div class="d-flex align-items-center gap-2">

                                <!-- Icon user -->
                                <div class="stat-icon bg-light text-coffee"
                                     style="width: 38px; height: 38px; font-size: 1.1rem;">
                                    <i class="bi bi-person"></i>
                                </div>

                                <div>
                                    <!-- Menampilkan nama user -->
                                    <span class="fw-bold text-dark d-block">
                                        {{ $user->name }}
                                    </span>

                                    <!-- Menampilkan label jika user yang login adalah dirinya sendiri -->
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25"
                                              style="font-size: 0.65rem;">
                                            Your User
                                        </span>
                                    @endif

                                </div>
                            </div>
                        </td>

                        <!-- Menampilkan email user -->
                        <td>{{ $user->email }}</td>

                        <!-- Menampilkan role user -->
                        <td>

                            <!-- Jika role Admin -->
                            @if(strtolower($user->role->name ?? '') === 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                    <i class="bi bi-shield-lock-fill me-1"></i> Admin
                                </span>

                            <!-- Jika bukan Admin, dianggap Cashier -->
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-person-badge me-1"></i> Cashier
                                </span>
                            @endif

                        </td>

                        <!-- Menampilkan tanggal dan jam registrasi user -->
                        <td class="text-muted small">
                            {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                        </td>

                        <!-- Tombol aksi -->
                        <td class="text-end">

                            <!-- Tombol edit user -->
                            <a href="{{ route('users.edit', $user->id) }}"
                               class="btn btn-sm btn-outline-warning text-dark me-1"
                               title="Edit Pengguna">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            <!-- Tombol hapus hanya muncul jika bukan akun yang sedang login -->
                            @if($user->id !== auth()->id())

                            <form action="{{ route('users.destroy', $user->id) }}"
                                  method="POST"
                                  class="d-inline">

                                <!-- Token keamanan Laravel -->
                                @csrf

                                <!-- Mengubah method menjadi DELETE -->
                                @method('DELETE')

                                <!-- Tombol konfirmasi hapus -->
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-delete-confirm"
                                        data-name="pengguna '{{ $user->name }}'"
                                        title="Hapus Pengguna">

                                    <i class="bi bi-trash"></i>
                                </button>

                            </form>

                            @endif

                        </td>

                    </tr>

                    <!-- Jika data user kosong -->
                    @empty

                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            No Users Found.
                        </td>
                    </tr>

                    @endforelse

                </tbody>
            </table>
        </div>

        <!-- Menampilkan navigasi halaman jika data lebih dari satu halaman -->
        @if($users->hasPages())
        <div class="card-footer bg-white border-top py-3">

            <!-- Pagination Laravel -->
            {{ $users->links() }}

        </div>
        @endif

    </div>
</div>
@endsection
