@extends('layouts.app')

@section('title', 'Laporan Penjualan - ' . $periodTitle)
@section('page_title', 'Laporan Penjualan')

@section('content')
<style>
    /* Styling Tampilan Cetak (Print) */
    @media print {
        @page {
            size: A4 portrait;
            margin: 12mm 10mm;
        }

        /* Sembunyikan elemen non-cetak */
        .no-print,
        .btn,
        form,
        .sidebar,
        .top-navbar,
        .actions-bar,
        .col-action,
        .action-col {
            display: none !important;
        }

        /* Tampilkan elemen khusus cetak */
        .print-only {
            display: block !important;
        }

        body {
            background: #ffffff !important;
            color: #000000 !important;
            font-size: 10pt !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        .container-fluid, .container {
            width: 100% !important;
            max-width: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        .card {
            border: none !important;
            box-shadow: none !important;
            background: transparent !important;
            margin-bottom: 10px !important;
        }

        .card-body, .card-header {
            padding: 0 !important;
        }

        /* Tabel Cetak */
        .table-report {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 9.5pt !important;
            margin-top: 10px !important;
        }

        .table-report th,
        .table-report td {
            border: 1px solid #333333 !important;
            padding: 5px 8px !important;
            color: #000000 !important;
        }

        .table-report thead th {
            background-color: #eaeaea !important;
            font-weight: bold !important;
            text-align: center !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .table-report tfoot td {
            background-color: #f2f2f2 !important;
            font-weight: bold !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        .table-report tr {
            page-break-inside: avoid !important;
        }

        thead {
            display: table-header-group !important;
        }

        /* Badge Cetak */
        .badge-print {
            border: 1px solid #666 !important;
            padding: 2px 5px !important;
            font-size: 8pt !important;
            font-weight: bold !important;
            text-transform: uppercase !important;
            color: #000 !important;
            background: transparent !important;
        }

        /* Garis Pembatas Kop Surat */
        .kop-divider {
            border-top: 2px solid #000 !important;
            border-bottom: 1px solid #000 !important;
            height: 3px !important;
            margin: 10px 0 15px 0 !important;
        }

        /* Area Tanda Tangan */
        .signature-section {
            page-break-inside: avoid !important;
            margin-top: 30px !important;
        }
    }

    /* Styling Tampilan Layar (Screen) */
    @media screen {
        .print-only {
            display: none !important;
        }
    }
</style>

<div class="container-fluid p-0">

    <!-- ========================================== -->
    <!-- KOP LAPORAN RESMI (Tampil Saat Di-Cetak) -->
    <!-- ========================================== -->
    <div class="print-only mb-3">
        <div class="text-center">
            <h2 class="fw-bold mb-0 text-uppercase" style="letter-spacing: 1px;">PPKD CAFE'S</h2>
            <p class="mb-0 small">Sistem Informasi Point of Sales &amp; Manajemen Kasir</p>
            <p class="mb-0 small text-muted">Jl. Budi Utomo No. 1, Jakarta Pusat | Telp: (021) 3845678</p>
        </div>
        <div class="kop-divider"></div>
        <div class="text-center mb-3">
            <h4 class="fw-bold text-uppercase mb-1" style="text-decoration: underline;">LAPORAN PENJUALAN TRANSAKSI</h4>
            <p class="mb-0 small">Periode: <strong>{{ $periodTitle }}</strong></p>
        </div>
        <div class="d-flex justify-content-between small text-muted mb-2">
            <div>Tanggal Cetak: <strong>{{ \Carbon\Carbon::now()->translatedFormat('d F Y, H:i') }} WIB</strong></div>
            <div>Dicetak Oleh: <strong>{{ auth()->user()->name ?? 'Administrator' }} ({{ ucfirst(auth()->user()->role->name ?? 'Admin') }})</strong></div>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- HEADER & FILTER (Layar Monitor / No-Print)  -->
    <!-- ========================================== -->
    <div class="card border-0 shadow-sm mb-4 no-print">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3 border-bottom pb-3">
                <div>
                    <h5 class="fw-bold text-coffee mb-1">
                        <i class="bi bi-file-earmark-bar-graph-fill me-2 text-gold"></i>Laporan Penjualan PPKD Cafe's
                    </h5>
                    <p class="text-muted small mb-0">Periode Aktif: <strong class="text-dark">{{ $periodTitle }}</strong></p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-coffee d-flex align-items-center gap-2 shadow-sm" onclick="window.print()">
                        <i class="bi bi-printer-fill"></i>
                        <span>Cetak Laporan</span>
                    </button>
                </div>
            </div>

            <!-- Form Filter Periode -->
            <form action="{{ route('reports.sales') }}" method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-semibold text-muted mb-1">Pilih Periode</label>
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

    <!-- ========================================== -->
    <!-- METRIK STATISTIK (Ringkasan Penjualan)     -->
    <!-- ========================================== -->
    <!-- Tampilan Layar: Metric Cards -->
    {{-- <div class="row g-3 mb-4 no-print">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: #3E2723; width: 48px; height: 48px;">
                        <i class="bi bi-cart-check fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Transaksi</span>
                        <h5 class="fw-bold mb-0 text-coffee">{{ $totalTransactions }} Transaksi</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: #6F4E37; width: 48px; height: 48px;">
                        <i class="bi bi-calculator fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Subtotal Bersih</span>
                        <h5 class="fw-bold mb-0 text-coffee">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-dark d-flex align-items-center justify-content-center" style="background: #EAD7C0; width: 48px; height: 48px;">
                        <i class="bi bi-percent fs-4 text-coffee"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Pajak PPN (11%)</span>
                        <h5 class="fw-bold mb-0 text-coffee">Rp {{ number_format($totalTax, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm p-3 h-100 bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-3 p-3 text-white d-flex align-items-center justify-content-center" style="background: #C49A6C; width: 48px; height: 48px;">
                        <i class="bi bi-cash-coin fs-4"></i>
                    </div>
                    <div>
                        <span class="text-muted small d-block">Total Omset Kotor</span>
                        <h5 class="fw-bold mb-0 text-coffee">Rp {{ number_format($totalAmount, 0, ',', '.') }}</h5>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Tampilan Layar: Metode Pembayaran -->
    <div class="row g-3 mb-4 no-print">
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
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2">Cash</span>
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
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2">QRIS</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Tampilan Cetak: Ringkasan Rekapitulasi Finansial -->
    <div class="print-only mb-3">
        <table class="table-report" style="margin-bottom: 15px;">
            <thead>
                <tr>
                    <th colspan="4" style="text-align: left; background-color: #dfdfdf !important;">
                        REKAPITULASI PENJUALAN
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="width: 25%;"><strong>Total Transaksi</strong></td>
                    <td style="width: 25%;">{{ $totalTransactions }} Transaksi</td>
                    <td style="width: 25%;"><strong>Total Tunai (Cash)</strong></td>
                    <td style="width: 25%;" class="text-end">Rp {{ number_format($cashAmount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Subtotal Bersih</strong></td>
                    <td class="text-end">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                    <td><strong>Total Non-Tunai (QRIS)</strong></td>
                    <td class="text-end">Rp {{ number_format($qrisAmount, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <td><strong>Pajak PPN (11%)</strong></td>
                    <td class="text-end">Rp {{ number_format($totalTax, 0, ',', '.') }}</td>
                    <td style="background-color: #f5f5f5;"><strong>TOTAL OMSET KOTOR</strong></td>
                    <td class="text-end" style="background-color: #f5f5f5; font-weight: bold;">
                        Rp {{ number_format($totalAmount, 0, ',', '.') }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- ========================================== -->
    <!-- TABEL RINCIAN TRANSAKSI PENJUALAN         -->
    <!-- ========================================== -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center no-print">
            <h6 class="fw-bold text-coffee mb-0">
                <i class="bi bi-table me-2 text-gold"></i>Daftar Rincian Transaksi Selesai ({{ $orders->count() }})
            </h6>
        </div>
        <div class="table-responsive">
            <table class="table table-modern table-report align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 40px;" class="text-center">No</th>
                        <th>No. Invoice</th>
                        <th>Tanggal &amp; Waktu</th>
                        <th>Kasir</th>
                        <th class="text-end">Subtotal</th>
                        <th class="text-end">PPN (11%)</th>
                        <th class="text-end">Total Akhir</th>
                        <th class="text-center">Metode</th>
                        <th class="text-center no-print col-action" style="width: 80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                        <td class="text-center fw-semibold text-muted">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-bold text-coffee">{{ $order->order_code }}</span>
                            <span class="d-block small text-muted no-print">{{ $order->orderDetails->count() }} jenis menu</span>
                        </td>
                        <td>
                            <span class="text-dark">{{ $order->order_date ? $order->order_date->translatedFormat('d M Y') : '-' }}</span>
                            <span class="small text-muted">{{ $order->order_date ? $order->order_date->format('H:i') : '' }} WIB</span>
                        </td>
                        <td>
                            <span class="fw-semibold text-dark">{{ $order->user->name ?? 'Kasir' }}</span>
                        </td>
                        <td class="text-end">{{ $order->formatted_subtotal }}</td>
                        <td class="text-end">{{ $order->formatted_tax }}</td>
                        <td class="text-end fw-bold text-coffee">{{ $order->formatted_amount }}</td>
                        <td class="text-center">
                            <span class="badge bg-light text-dark border text-uppercase px-2 py-1 badge-print">
                                {{ $order->payment_method }}
                            </span>
                        </td>
                        <td class="text-center no-print col-action">
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-coffee" title="Detail Transaksi">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox fs-1 d-block mb-2 text-secondary no-print"></i>
                            Tidak ada data transaksi yang selesai untuk periode <strong>{{ $periodTitle }}</strong>.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($orders->isNotEmpty())
                <tfoot>
                    <tr>
                        <td colspan="4" class="text-end fw-bold">TOTAL KESELURUHAN:</td>
                        <td class="text-end fw-bold">Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($totalTax, 0, ',', '.') }}</td>
                        <td class="text-end fw-bold">Rp {{ number_format($totalAmount, 0, ',', '.') }}</td>
                        <td class="no-print col-action" colspan="2"></td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- ========================================== -->
    <!-- TANDA TANGAN PENGESAHAN (Khusus Cetak)     -->
    <!-- ========================================== -->
    <div class="print-only signature-section mt-4">
        <div class="row" style="display: flex; justify-content: space-between; margin-top: 30px;">
            <div style="width: 40%; text-align: center;">
                <p class="mb-1">Petugas / Kasir,</p>
                <div style="height: 60px;"></div>
                <p class="mb-0 fw-bold" style="text-decoration: underline;">{{ auth()->user()->name ?? 'Administrator' }}</p>
                <p class="small text-muted mb-0">{{ ucfirst(auth()->user()->role->name ?? 'Kasir') }}</p>
            </div>
            <div style="width: 40%; text-align: center;">
                <p class="mb-1">Jakarta, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                <p class="mb-1">Mengetahui, Pimpinan</p>
                <div style="height: 60px;"></div>
                <p class="mb-0 fw-bold" style="text-decoration: underline;">( ......................................... )</p>
                <p class="small text-muted mb-0">Penanggung Jawab Outlet</p>
            </div>
        </div>
    </div>

</div>
@endsection
