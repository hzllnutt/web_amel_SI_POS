@extends('layouts.app')

@section('title', 'Dashboard')
{{-- @section('page_title', 'Ringkasan & Analitik Bisnis') --}}

@section('content')
<div class="container-fluid p-0">
    <!-- Welcome Banner -->
    <div class="card mb-4 border-0 text-white" style="background: linear-gradient(135deg, #3E2723 0%, #6F4E37 100%); border-radius: 16px;">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-warning text-dark px-3 py-1 mb-2 fw-semibold">PPKD Cafe's</span>
                <h3 class="fw-bold mb-1">Halo, {{ auth()->user()->name }}! ☕</h3>
                {{-- <p class="mb-0 text-white-50">Berikut adalah ikhtisar performa penjualan dan inventaris toko Anda hari ini.</p> --}}
            </div>
            <div>
                <a href="{{ route('pos.index') }}" class="btn btn-gold btn-lg px-4 d-inline-flex align-items-center gap-2 shadow">
                    <i class="bi bi-calculator-fill fs-5"></i>
                    <span>Cashier (POS)</span>
                </a>
            </div>
        </div>
    </div>

    <!-- 6 Summary Stat Cards -->
    <div class="row g-3 mb-4">
        <!-- Card 1: Penjualan Hari Ini -->
        <div class="col-12 col-md-6">
            <div class="card stat-card border-0 h-100" style="background: #FFFFFF;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Penjualan Hari Ini</span>
                        <h4 class="fw-bold text-coffee mb-0 mt-1">Rp {{ number_format($todaySales, 0, ',', '.') }}</h4>
                        <span class="badge bg-success bg-opacity-10 text-success mt-2 small">
                            <i class="bi bi-calendar-check me-1"></i> {{ now()->translatedFormat('d F Y') }}
                        </span>
                    </div>
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-cash-stack"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Transaksi Hari Ini -->
        {{-- <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card border-0 h-100" style="background: #FFFFFF;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Transaksi Hari Ini</span>
                        <h4 class="fw-bold text-coffee mb-0 mt-1">{{ number_format($todayTransactions) }} Pesanan</h4>
                        <span class="text-muted small mt-2 d-block">Status: Selesai</span>
                    </div>
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-receipt"></i>
                    </div>
                </div>
            </div>
        </div> --}}

        <!-- Card 3: Penjualan Bulan Ini -->
        <div class="col-12 col-md-6">
            <div class="card stat-card border-0 h-100" style="background: #FFFFFF;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Penjualan Bulan Ini</span>
                        <h4 class="fw-bold text-coffee mb-0 mt-1">Rp {{ number_format($monthSales, 0, ',', '.') }}</h4>
                        <span class="badge bg-info bg-opacity-10 text-info mt-2 small">
                            <i class="bi bi-graph-up me-1"></i> Bulan {{ now()->translatedFormat('F Y') }}
                        </span>
                    </div>
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-wallet2"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: Total Produk -->
        <div class="col-12 col-md-6">
            <div class="card stat-card border-0 h-100" style="background: #FFFFFF;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Total Produk</span>
                        <h4 class="fw-bold text-coffee mb-0 mt-1">{{ number_format($totalProducts) }} Menu</h4>
                        <a href="{{ route('products.index') }}" class="small text-decoration-none text-coffee fw-semibold mt-2 d-inline-block">
                            Lihat Menu &rarr;
                        </a>
                    </div>
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: Total Kategori -->
        <div class="col-12 col-md-6">
            <div class="card stat-card border-0 h-100" style="background: #FFFFFF;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Total Kategori</span>
                        <h4 class="fw-bold text-coffee mb-0 mt-1">{{ number_format($totalCategories) }} Kategori</h4>
                        <a href="{{ route('categories.index') }}" class="small text-decoration-none text-coffee fw-semibold mt-2 d-inline-block">
                            Lihat Kategori &rarr;
                        </a>
                    </div>
                    <div class="stat-icon bg-secondary bg-opacity-10 text-secondary">
                        <i class="bi bi-tags"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 6: Stok Rendah -->
        {{-- <div class="col-12 col-sm-6 col-xl-4">
            <div class="card stat-card border-0 h-100" style="background: #FFFFFF;">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Produk Stok Rendah</span>
                        <h4 class="fw-bold text-danger mb-0 mt-1">{{ number_format($lowStockProducts) }} Produk</h4>
                        <span class="text-muted small mt-2 d-block">Stok &le; 5 item</span>
                    </div>
                    <div class="stat-icon bg-danger bg-opacity-10 text-danger">
                        <i class="bi bi-exclamation-triangle"></i>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Charts Section -->
    {{-- <div class="row g-4 mb-4">
        <!-- 7 Days Sales Line Chart -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 h-100 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-coffee mb-0">
                        <i class="bi bi-graph-up-arrow me-2 text-gold"></i>Tren Penjualan 7 Hari Terakhir
                    </h6>
                    <span class="badge bg-light text-dark border">Rupiah (IDR)</span>
                </div>
                <div class="card-body p-3">
                    <div style="height: 280px; position: relative;">
                        <canvas id="sevenDaysSalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Selling Products Doughnut -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 h-100 shadow-sm">
                <div class="card-header bg-white border-bottom py-3">
                    <h6 class="fw-bold text-coffee mb-0">
                        <i class="bi bi-pie-chart-fill me-2 text-gold"></i>5 Menu Terlaris
                    </h6>
                </div>
                <div class="card-body p-3 d-flex flex-column align-items-center justify-content-center">
                    @if(count($topProductNames) > 0)
                        <div style="height: 230px; width: 100%; position: relative;">
                            <canvas id="topProductsChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1"></i>
                            <p class="mt-2 small">Belum ada transaksi completed untuk analisis produk.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Monthly Sales Bar Chart -->
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-coffee mb-0">
                        <i class="bi bi-bar-chart-fill me-2 text-gold"></i>Penjualan Bulanan (Tahun {{ now()->year }})
                    </h6>
                    <span class="badge bg-light text-dark border">12 Bulan</span>
                </div>
                <div class="card-body p-3">
                    <div style="height: 260px; position: relative;">
                        <canvas id="monthlySalesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Recent Orders Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-coffee mb-0">
                <i class="bi bi-clock-history me-2 text-gold"></i>Transaksi Terbaru
            </h6>
            <a href="{{ route('orders.index') }}" class="btn btn-sm btn-outline-coffee">
                Lihat Semua Transaksi &rarr;
            </a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th>Invoice</th>
                        <th>Tanggal</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                    <tr>
                        <td class="fw-bold text-coffee">
                            <a href="{{ route('orders.show', $order->id) }}" class="text-decoration-none text-coffee">
                                {{ $order->order_code }}
                            </a>
                        </td>
                        <td>{{ $order->order_date->format('d/m/Y H:i') }}</td>
                        <td>
                            <span class="d-inline-flex align-items-center gap-1">
                                <i class="bi bi-person small text-muted"></i>
                                {{ $order->user->name ?? 'Kasir' }}
                            </span>
                        </td>
                        <td class="fw-bold text-dark">{{ $order->formatted_amount }}</td>
                        <td>
                            <span class="badge bg-light text-dark border text-uppercase">
                                {{ $order->payment_method }}
                            </span>
                        </td>
                        <td>
                            @if($order->order_status === 'completed')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Completed
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Cancelled
                                </span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-secondary" title="Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-coffee" title="Cetak Struk">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            <i class="bi bi-receipt-cutoff fs-2 d-block mb-2"></i>
                            Belum ada transaksi tercatat. Mulai buat transaksi di menu Kasir (POS).
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Seven Days Sales Chart
    const sevenDaysCtx = document.getElementById('sevenDaysSalesChart');
    if (sevenDaysCtx) {
        new Chart(sevenDaysCtx, {
            type: 'line',
            data: {
                labels: {!! json_encode($last7Days) !!},
                datasets: [{
                    label: 'Total Penjualan (Rp)',
                    data: {!! json_encode($last7DaysSales) !!},
                    borderColor: '#6F4E37',
                    backgroundColor: 'rgba(111, 78, 55, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#C49A6C',
                    pointBorderColor: '#FFFFFF',
                    pointBorderWidth: 2,
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) {
                                return 'Rp ' + val.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }

    // 2. Top Products Doughnut Chart
    const topProductsCtx = document.getElementById('topProductsChart');
    if (topProductsCtx) {
        new Chart(topProductsCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($topProductNames) !!},
                datasets: [{
                    data: {!! json_encode($topProductQuantities) !!},
                    backgroundColor: [
                        '#6F4E37',
                        '#C49A6C',
                        '#3E2723',
                        '#A1887F',
                        '#D7CCC8'
                    ],
                    borderWidth: 2,
                    borderColor: '#FFFFFF'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { boxWidth: 12, padding: 12 }
                    }
                }
            }
        });
    }

    // 3. Monthly Sales Bar Chart
    const monthlyCtx = document.getElementById('monthlySalesChart');
    if (monthlyCtx) {
        new Chart(monthlyCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($monthsLabels) !!},
                datasets: [{
                    label: 'Penjualan Bulanan (Rp)',
                    data: {!! json_encode($monthlySales) !!},
                    backgroundColor: '#C49A6C',
                    borderRadius: 6,
                    hoverBackgroundColor: '#6F4E37'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(val) {
                                return 'Rp ' + val.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    }
});
</script>
@endpush
