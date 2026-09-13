@extends('layouts.app')

@section('title', 'Laporan Penjualan - ' . $periodTitle)
@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Actions & Period Filter -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 border-bottom pb-3">
                <div>
                    <h5 class="fw-bold text-coffee mb-1">
                        <i class="bi bi-file-earmark-bar-graph-fill me-2 text-gold"></i>Laporan Penjualan el'sCoffe
                    </h5>
                    <p class="text-muted small mb-0">Periode: <strong class="text-dark">{{ $periodTitle }}</strong></p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-coffee d-flex align-items-center gap-2" onclick="window.print()">
                        <i class="bi bi-printer"></i>
                        <span>Cetak Laporan</span>
                    </button>
                </div>
            </div>

            <!-- Filter Buttons & Options -->
            <form action="{{ route('reports.sales') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Pilih Jenis Periode</label>
                    <div class="btn-group w-100" role="group">
                        <a href="{{ route('reports.sales', ['period' => 'daily', 'date' => $date]) }}" 
                           class="btn btn-sm {{ $period === 'daily' ? 'btn-coffee' : 'btn-outline-coffee' }}">
                           Harian
                        </a>
                        <a href="{{ route('reports.sales', ['period' => 'weekly']) }}" 
                           class="btn btn-sm {{ $period === 'weekly' ? 'btn-coffee' : 'btn-outline-coffee' }}">
                           Mingguan
                        </a>
                        <a href="{{ route('reports.sales', ['period' => 'monthly', 'month' => $month, 'year' => $year]) }}" 
                           class="btn btn-sm {{ $period === 'monthly' ? 'btn-coffee' : 'btn-outline-coffee' }}">
                           Bulanan
                        </a>
                    </div>
                </div>

                @if($period === 'daily')
                <div class="col-12 col-sm-6 col-md-4">
                    <label for="date" class="form-label small fw-semibold text-muted mb-1">Pilih Tanggal</label>
                    <div class="input-group input-group-sm">
                        <input type="hidden" name="period" value="daily">
                        <input type="date" name="date" id="date" class="form-control" value="{{ $date }}">
                        <button type="submit" class="btn btn-coffee">
                            <i class="bi bi-search me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
                @elseif($period === 'monthly')
                <div class="col-6 col-md-3">
                    <label for="month" class="form-label small fw-semibold text-muted mb-1">Bulan</label>
                    <input type="hidden" name="period" value="monthly">
                    <select name="month" id="month" class="form-select form-select-sm">
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ (int)$month === $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->translatedFormat('F') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="col-6 col-md-3">
                    <label for="year" class="form-label small fw-semibold text-muted mb-1">Tahun</label>
                    <div class="input-group input-group-sm">
                        <select name="year" id="year" class="form-select form-select-sm">
                            @for($y = \Carbon\Carbon::now()->year - 2; $y <= \Carbon\Carbon::now()->year; $y++)
                                <option value="{{ $y }}" {{ (int)$year === $y ? 'selected' : '' }}>{{ $y }}</option>
                            @endfor
                        </select>
                        <button type="submit" class="btn btn-coffee">
                            <i class="bi bi-search me-1"></i> Tampilkan
                        </button>
                    </div>
                </div>
                @endif
            </form>
        </div>
    </div>

    <!-- Summary Metrics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: #3E2723; width: 50px; height: 50px;">
                        <i class="bi bi-cart-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Transaksi</span>
                        <h4 class="fw-bold mb-0 text-coffee">{{ $totalTransactions }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: #6F4E37; width: 50px; height: 50px;">
                        <i class="bi bi-calculator fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Subtotal Bersih</span>
                        <h4 class="fw-bold mb-0 text-coffee">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-dark d-flex align-items-center justify-content-center" style="background: #EAD7C0; width: 50px; height: 50px;">
                        <i class="bi bi-percent fs-4 text-coffee"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Pajak PPN (11%)</span>
                        <h4 class="fw-bold mb-0 text-coffee">Rp {{ number_format($totalTax, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: #C49A6C; width: 50px; height: 50px;">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Omset Kotor</span>
                        <h4 class="fw-bold mb-0 text-coffee">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Methods Split -->
    <div class="row g-3 mb-4">
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-cash-stack fs-2 text-success"></i>
                        <div>
                            <span class="text-muted small">Pembayaran Tunai (Cash)</span>
                            <h5 class="fw-bold mb-0 text-dark">Rp {{ number_format($cashAmount, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <span class="badge bg-light text-success border">Cash</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card border-0 shadow-sm p-3 bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-qr-code-scan fs-2 text-primary"></i>
                        <div>
                            <span class="text-muted small">Pembayaran Non-Tunai (QRIS)</span>
                            <h5 class="fw-bold mb-0 text-dark">Rp {{ number_format($qrisAmount, 0, ',', '.') }}</h5>
                        </div>
                    </div>
                    <span class="badge bg-light text-primary border">QRIS</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions List Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-coffee mb-0">
                <i class="bi bi-table me-2 text-gold"></i>Rincian Transaksi Selesai ({{ $orders->count() }})
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>No. Invoice</th>
                        <th>Waktu Transaksi</th>
                        <th>Kasir</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">Pajak (11%)</th>
                        <th class="text-end">Total Akhir</th>
                        <th class="text-center">Metode</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                        <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-bold text-coffee">{{ $order->order_code }}</span>
                            <span class="d-block small text-muted">{{ $order->orderDetails->count() }} jenis menu</span>
                        </td>
                        <td>
                            <span class="text-dark">{{ $order->order_date ? $order->order_date->translatedFormat('d M Y') : '-' }}</span>
                            <span class="d-block small text-muted">{{ $order->order_date ? $order->order_date->format('H:i') : '' }} WIB</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $order->user->name ?? 'Kasir' }}</span>
                        </td>
                        <td class="text-end text-muted">{{ $order->formatted_subtotal }}</td>
                        <td class="text-end text-muted">{{ $order->formatted_tax }}</td>
                        <td class="text-end fw-bold text-coffee">{{ $order->formatted_amount }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-uppercase border text-coffee px-2 py-1">
                                {{ $order->payment_method }}
                            </span>
                        </td>
                        <td class="text-center">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-coffee" title="Detail Transaksi">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary"></i>
                            Tidak ada data transaksi untuk periode <strong>{{ $periodTitle }}</strong>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($orders->isNotEmpty())
                <tfoot class="bg-light fw-bold">
                    <tr>
                        <td colspan="4" class="text-end text-coffee">TOTAL KESELURUHAN:</td>
                        <td class="text-end text-coffee">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                        <td class="text-end text-coffee">Rp {{ number_format($totalTax, 0, ',', '.') }}</td>
                        <td class="text-end text-coffee fs-6">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>
</div>
@endsection
