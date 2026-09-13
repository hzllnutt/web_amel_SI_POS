@extends('layouts.app')

@section('title', 'Detail Transaksi ' . $order->order_code)
@section('page_title', 'Detail Pesanan & Transaksi')

@section('content')
<div class="container-fluid p-0">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <!-- Header Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h4 class="fw-bold text-coffee mb-0">{{ $order->order_code }}</h4>
                            @if($order->order_status === 'completed')
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1">
                                    <i class="bi bi-check-circle me-1"></i> Completed
                                </span>
                            @else
                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-2 py-1">
                                    <i class="bi bi-x-circle me-1"></i> Cancelled
                                </span>
                            @endif
                        </div>
                        <p class="text-muted small mb-0">
                            Waktu Transaksi: {{ $order->order_date->translatedFormat('d F Y, H:i') }} WIB &middot; Kasir: <strong>{{ $order->user->name ?? 'Kasir' }}</strong>
                        </p>
                    </div>

                    <div class="d-flex gap-2">
                        <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-coffee d-inline-flex align-items-center gap-2">
                            <i class="bi bi-printer"></i>
                            <span>Cetak Struk</span>
                        </a>

                        @if($order->order_status === 'completed')
                        <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="button" class="btn btn-outline-danger btn-cancel-order d-inline-flex align-items-center gap-1" 
                                    data-code="{{ $order->order_code }}">
                                <i class="bi bi-x-circle"></i>
                                <span>Batalkan Transaksi</span>
                            </button>
                        </form>
                        @endif

                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
            </div>

            <!-- Detail Breakdown Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-bottom p-3">
                    <h6 class="fw-bold text-coffee mb-0">
                        <i class="bi bi-list-check me-2 text-gold"></i>Rincian Item Menu Dipesan
                    </h6>
                </div>
                <div class="table-responsive">
                    <table class="table table-modern align-middle mb-0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Produk Menu</th>
                                <th>Kategori</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-center">Jumlah (Qty)</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderDetails as $index => $detail)
                            <tr>
                                <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $detail->product->photo_url ?? asset('images/default-coffee.svg') }}" 
                                             alt="{{ $detail->product->product_name ?? 'Produk' }}" 
                                             class="rounded border" style="width: 44px; height: 44px; object-fit: cover;">
                                        <div>
                                            <span class="fw-bold text-dark d-block">{{ $detail->product->product_name ?? 'Produk Dihapus' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $detail->product->category->category_name ?? '-' }}
                                    </span>
                                </td>
                                <td class="text-end text-muted">{{ $detail->formatted_price }}</td>
                                <td class="text-center fw-bold">{{ $detail->order_quantity }}</td>
                                <td class="text-end fw-bold text-coffee">{{ $detail->formatted_subtotal }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Financial Summary Box -->
                <div class="card-footer bg-light p-4">
                    <div class="row justify-content-end">
                        <div class="col-12 col-md-5">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Metode Pembayaran:</span>
                                <span class="badge bg-coffee text-white text-uppercase px-3 py-1">{{ $order->payment_method }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal:</span>
                                <span class="fw-semibold text-dark">{{ $order->formatted_subtotal }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Pajak (11%):</span>
                                <span class="fw-semibold text-dark">{{ $order->formatted_tax }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Total Pembayaran:</span>
                                <span class="fw-bold fs-5 text-coffee">{{ $order->formatted_amount }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Uang Dibayarkan:</span>
                                <span class="fw-semibold text-dark">{{ $order->formatted_paid }}</span>
                            </div>
                            <hr class="my-2 border-secondary border-opacity-25">
                            <div class="d-flex justify-content-between">
                                <span class="fw-bold text-secondary">Kembalian:</span>
                                <span class="fw-bold fs-5 text-success">{{ $order->formatted_change }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/orders.js') }}"></script>
@endpush
