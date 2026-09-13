<?php

// Import Controller yang dibutuhkan untuk menghandle request
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// --- ROUTE GUEST (Belum Login) ---
// Pengguna yang belum login hanya bisa mengakses halaman ini
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// --- ROUTE AUTH (Sudah Login) ---
// Semua route di bawah ini wajib login terlebih dahulu
Route::middleware('auth')->group(function () {

    // Process Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Halaman utama (/) langsung diarahkan (redirect) ke dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Halaman Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // --- FITUR KASIR / POS ---
    // Hanya bisa diakses oleh user dengan role 'admin' atau 'kasir'
    Route::middleware('role:admin,kasir')->group(function () {
        Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
        Route::get('/pos/products', [POSController::class, 'getProducts'])->name('pos.products');
        Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    });

    // --- MANAJEMEN KATEGORI ---
    // Semua user terautentikasi bisa melihat data, tapi aksinya dibatasi oleh controller
    Route::resource('categories', CategoryController::class)->except(['create', 'show', 'edit']);

    // --- MANAJEMEN PRODUK ---
    // CRUD lengkap untuk produk (index, create, store, show, edit, update, destroy)
    Route::resource('products', ProductController::class);

    // --- RIWAYAT DAN DETAIL TRANSAKSI ---
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/receipt', [OrderController::class, 'receipt'])->name('orders.receipt');

    // --- KHUSUS ADMIN ---
    // Fitur pembatalan pesanan dan manajemen user (CRUD User) hanya untuk Admin
    Route::middleware('role:admin')->group(function () {
        Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
        Route::resource('users', UserController::class);
    });

    // --- LAPORAN PENJUALAN ---
    // Hanya bisa diakses oleh 'admin' atau 'pimpinan'
    Route::middleware('role:admin,pimpinan')->group(function () {
        Route::get('/reports/sales', [OrderController::class, 'report'])->name('reports.sales');
    });
});
