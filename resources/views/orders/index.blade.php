@extends('layouts.app')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat & Rekap Transaksi')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold text-coffee mb-1">Sales Transaction History</h5>
                {{-- <p class="text-muted small mb-0"></p> --}}
            </div>
            <a href="{{ route('pos.index') }}" class="btn btn-coffee d-inline-flex align-items-center gap-2">
                <i class="bi bi-plus-circle"></i>
                <span>New Transaction in Cashier (POS)</span>
            </a>
        </div>
    </div>

    <!-- Filter Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-3">
            <form action="{{ route('orders.index') }}" method="GET" class="row g-2 align-items-center">
                <div class="col-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" name="search" class="form-control bg-light border-start-0"
                               placeholder="Search invoice..." value="{{ $search }}">
                    </div>
                </div>

                <div class="col-6 col-md-3">
                    <input type="date" name="date" class="form-control bg-light" value="{{ $date }}">
                </div>

                <div class="col-6 col-md-2">
                    <select name="payment_method" class="form-select bg-light">
                        <option value="">-- All Methods --</option>
                        <option value="cash" {{ $payment === 'cash' ? 'selected' : '' }}>Cash</option>
                        <option value="qris" {{ $payment === 'qris' ? 'selected' : '' }}>QRIS</option>
                        {{-- <option value="debit" {{ $payment === 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="ewallet" {{ $payment === 'ewallet' ? 'selected' : '' }}>E-Wallet</option> --}}
                    </select>
                </div>

                <div class="col-6 col-md-2">
                    <select name="order_status" class="form-select bg-light">
                        <option value="">-- All Status --</option>
                        <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>

                <div class="col-6 col-md-2 d-flex gap-2">
                    <button type="submit" class="btn btn-coffee w-100">
                        <i class="bi bi-funnel me-1"></i> Filter
                    </button>
                    @if($search || $date || $payment || $status)
                        <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary" title="Reset Filter">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table Card -->
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover table-modern align-middle mb-0">
                <thead>
                    <tr>
                        <th style="width: 50px;">No</th>
                        <th>Order Code</th>
                        <th>Date & Time</th>
                        <th>Cashier</th>
                        <th>Total</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th class="text-end" style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $index => $order)
                    <tr>
                        <td class="fw-bold text-muted">{{ $orders->firstItem() + $index }}</td>
                        <td>
                            <a href="{{ route('orders.show', $order->id) }}" class="fw-bold text-coffee text-decoration-none">
                                {{ $order->order_code }}
                            </a>
                        </td>
                        <td>{{ $order->order_date ? $order->order_date->format('d/m/Y H:i') : '-' }}</td>
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
                            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-info me-1" title="Lihat Detail">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="btn btn-sm btn-outline-coffee" title="Cetak Struk">
                                <i class="bi bi-printer"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5 text-muted">
                            <i class="bi bi-receipt fs-1 d-block mb-2"></i>
                            There's no transaction that matches the filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($orders->hasPages())
        <div class="card-footer bg-white border-top py-3">
            {{ $orders->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
