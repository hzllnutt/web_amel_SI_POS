<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Point of Sales') - PPKD Cafe's</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.min.css">
    <!-- Custom Theme CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body>
<div class="app-wrapper">
    <!-- Desktop Sidebar -->
    <aside class="sidebar d-none d-lg-flex no-print">
        <a href="{{ route('dashboard') }}" class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="bi bi-cup-hot-fill"></i>
            </div>
            <div class="sidebar-brand-text">PPKD <span>Cafe's</span></div>
        </a>

        <ul class="sidebar-menu">
            <li class="sidebar-item">
                <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-1x2-fill"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isKasir()))
            <li class="sidebar-item">
                <a href="{{ route('pos.index') }}" class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                    <i class="bi bi-calculator-fill"></i>
                    <span>Cashier (POS)</span>
                </a>
            </li>
            @endif
            <li class="sidebar-item">
                <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                    <i class="bi bi-box-seam-fill"></i>
                    <span>{{ (auth()->check() && auth()->user()->isAdmin()) ? 'Product' : 'Stock Product' }}</span>
                </a>
            </li>
            @if(auth()->check() && auth()->user()->isAdmin())
            <li class="sidebar-item">
                <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                    <i class="bi bi-tag-fill"></i>
                    <span>Category</span>
                </a>
            </li>
            @endif
            <li class="sidebar-item">
                <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                    <i class="bi bi-receipt"></i>
                    <span>Transaction</span>
                </a>
            </li>
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isPimpinan()))
            <li class="sidebar-item">
                <a href="{{ route('reports.sales') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                    <i class="bi bi-file-earmark-bar-graph-fill"></i>
                    <span>Sales Report</span>
                </a>
            </li>
            @endif
            @if(auth()->check() && auth()->user()->isAdmin())
            <li class="sidebar-item">
                <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>User Management</span>
                </a>
            </li>
            @endif
        </ul>

        <div class="p-3 border-top border-secondary border-opacity-25">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Offcanvas Sidebar -->
    <div class="offcanvas offcanvas-start bg-dark text-light no-print" tabindex="-1" id="mobileSidebar" aria-labelledby="mobileSidebarLabel">
        <div class="offcanvas-header border-bottom border-secondary border-opacity-25">
            <h5 class="offcanvas-title d-flex align-items-center gap-2" id="mobileSidebarLabel">
                <i class="bi bi-cup-hot-fill text-warning"></i>
                <span class="fw-bold">PPKD <span class="text-warning">Cafe's</span></span>
            </h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column p-0">
            <ul class="sidebar-menu p-3 flex-grow-1">
                <li class="sidebar-item">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isKasir()))
                <li class="sidebar-item">
                    <a href="{{ route('pos.index') }}" class="sidebar-link {{ request()->routeIs('pos.*') ? 'active' : '' }}">
                        <i class="bi bi-calculator-fill"></i>
                        <span>Kasir (POS)</span>
                    </a>
                </li>
                @endif
                <li class="sidebar-item">
                    <a href="{{ route('products.index') }}" class="sidebar-link {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <i class="bi bi-box-seam-fill"></i>
                        <span>{{ (auth()->check() && auth()->user()->isAdmin()) ? 'Produk' : 'Stok Produk' }}</span>
                    </a>
                </li>
                @if(auth()->check() && auth()->user()->isAdmin())
                <li class="sidebar-item">
                    <a href="{{ route('categories.index') }}" class="sidebar-link {{ request()->routeIs('categories.*') ? 'active' : '' }}">
                        <i class="bi bi-tag-fill"></i>
                        <span>Kategori</span>
                    </a>
                </li>
                @endif
                <li class="sidebar-item">
                    <a href="{{ route('orders.index') }}" class="sidebar-link {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <i class="bi bi-receipt"></i>
                        <span>Transaksi</span>
                    </a>
                </li>
                @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isPimpinan()))
                <li class="sidebar-item">
                    <a href="{{ route('reports.sales') }}" class="sidebar-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        <span>Laporan Penjualan</span>
                    </a>
                </li>
                @endif
                @if(auth()->check() && auth()->user()->isAdmin())
                <li class="sidebar-item">
                    <a href="{{ route('users.index') }}" class="sidebar-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Manajemen User</span>
                    </a>
                </li>
                @endif
            </ul>
            <div class="p-3 border-top border-secondary border-opacity-25">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-outline-light w-100 d-flex align-items-center justify-content-center gap-2 py-2">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- Top Navbar -->
        <header class="top-navbar no-print">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <div class="d-none d-sm-block">
                    <strong class="text-muted small">Point of Sales</strong>
                    {{-- <h6 class="mb-0 fw-bold text-coffee">@yield('page_title', 'el\'sCoffe')</h6> --}}
                </div>
            </div>

            <!-- User Status & Dropdown -->
            <div class="d-flex align-items-center gap-3">
                <div class="user-badge">
                    <i class="bi bi-person-circle fs-5 text-coffee"></i>
                    <div class="d-flex flex-column text-start">
                        <span class="fw-bold small text-dark">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                        <span class="badge {{ (auth()->user()->role->name ?? '') === 'admin' ? 'bg-danger' : 'bg-success' }} px-2 py-0" style="font-size: 0.65rem; width: fit-content;">
                            {{ strtoupper(auth()->user()->role->name ?? 'Kasir') }}
                        </span>
                    </div>
                </div>

                <div class="dropdown">
                    <button class="btn btn-sm btn-light border dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-gear"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                        <li><h6 class="dropdown-header">{{ auth()->user()->email ?? '' }}</h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger d-flex align-items-center gap-2">
                                    <i class="bi bi-box-arrow-right"></i> Keluar (Logout)
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic Body Content -->
        <main class="flex-grow-1 @yield('main_class', 'p-3 p-md-4')">
            @yield('content')
        </main>
    </div>
</div>

<!-- Bootstrap 5 Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.6/dist/sweetalert2.all.min.js"></script>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<!-- Custom App JS -->
<script src="{{ asset('js/app.js') }}"></script>

<!-- Flash Message Notifications -->
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Toast.fire({
            icon: 'success',
            title: "{{ session('success') }}"
        });
    });
</script>
@endif

@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: "{{ session('error') }}",
            confirmButtonColor: '#3E2723'
        });
    });
</script>
@endif

@if($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function () {
        Swal.fire({
            icon: 'warning',
            title: 'Periksa Kembali Input Anda',
            html: '<ul class="text-start mb-0">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
            confirmButtonColor: '#3E2723'
        });
    });
</script>
@endif

@stack('scripts')
</body>
</html>
