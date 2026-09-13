@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page_title', 'Manajemen Pengguna & Hak Akses')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold text-coffee mb-1">Daftar Akun Pengguna</h5>
                <p class="text-muted small mb-0">Kelola akun administrator dan kasir el'sCoffe.</p>
            </div>
            <a href="{{ route('users.create') }}" class="btn btn-coffee d-inline-flex align-items-center gap-2">
                <i class="bi bi-person-plus-fill"></i>
                <span>Tambah Pengguna</span>
            </a>
        </div>
    </div>

    <!-- Users Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3">
            <form action="{{ route('users.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-start-0" 
                               placeholder="Cari nama atau email pengguna..." value="{{ $search }}">
                        @if($search)
                            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Reset</a>
                        @endif
                    </div>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-outline-coffee">
                        <i class="bi bi-filter me-1"></i> Cari
                    </button>
                </div>
            </form>
        </div>

        <div class="table-responsive">
            <table class="table table-hover table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 60px;">No</th>
                        <th>Nama Pengguna</th>
                        <th>Alamat Email</th>
                        <th>Peran (Role)</th>
                        <th>Terdaftar Sejak</th>
                        <th class="text-end" style="width: 140px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $index => $user)
                    <tr>
                        <td class="fw-bold text-muted">{{ $users->firstItem() + $index }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="stat-icon bg-light text-coffee" style="width: 38px; height: 38px; font-size: 1.1rem;">
                                    <i class="bi bi-person"></i>
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block">{{ $user->name }}</span>
                                    @if($user->id === auth()->id())
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25" style="font-size: 0.65rem;">
                                            Akun Anda
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if(strtolower($user->role->name ?? '') === 'admin')
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                    <i class="bi bi-shield-lock-fill me-1"></i> ADMIN
                                </span>
                            @else
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-person-badge me-1"></i> KASIR
                                </span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $user->created_at ? $user->created_at->format('d M Y, H:i') : '-' }}
                        </td>
                        <td class="text-end">
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning text-dark me-1" title="Edit Pengguna">
                                <i class="bi bi-pencil-square"></i>
                            </a>

                            @if($user->id !== auth()->id())
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-confirm" 
                                        data-name="pengguna '{{ $user->name }}'"
                                        title="Hapus Pengguna">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-1 d-block mb-2"></i>
                            Tidak ada pengguna ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
