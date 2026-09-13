@extends('layouts.app')

@section('title', 'Point of Sale (POS)')
@section('page_title', 'Kasir (Terminal POS)')
@section('main_class', 'p-0')

@section('content')
<div class="pos-container">
    <!-- Column 1: Categories Nav -->
    <aside class="pos-category-nav">
        <div class="d-none d-lg-block mb-3">
            <span class="text-muted small fw-bold text-uppercase" style="letter-spacing: 0.5px;">Kategori Menu</span>
        </div>

        <button type="button" class="category-pill-btn active" data-category="all">
            <span><i class="bi bi-grid-fill me-2 text-warning"></i> Semua</span>
        </button>

        @foreach($categories as $cat)
        <button type="button" class="category-pill-btn" data-category="{{ $cat->id }}">
            <span class="text-truncate me-1">{{ $cat->category_name }}</span>
            <span class="badge bg-light text-dark border small">{{ $cat->products_count }}</span>
        </button>
        @endforeach
    </aside>

    <!-- Column 2: Products Catalog Area -->
    <section class="pos-products-area d-flex flex-column">
        <!-- Top Search Bar & Counter -->
        <div class="mb-3 d-flex flex-column flex-sm-row gap-2 justify-content-between align-items-sm-center">
            <div class="input-group" style="max-width: 400px;">
                <span class="input-group-text bg-white border-end-0 text-muted">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" id="posSearchInput" class="form-control bg-white border-start-0"
                       placeholder="Ketik nama menu, misal: Americano...">
            </div>
            <div class="text-muted small">
                <i class="bi bi-info-circle me-1"></i> Klik menu untuk menambah ke pesanan.
            </div>
        </div>

        <!-- Products Grid Container -->
        <div id="posProductsGrid" class="row g-3">
            <!-- Dynamically populated via pos.js fetchProducts() -->
        </div>
    </section>

    <!-- Column 3: Cart Panel -->
    <aside class="pos-cart-panel">
        <!-- Cart Header -->
        <div class="p-3 border-bottom d-flex align-items-center justify-content-between bg-white">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-cart-fill text-coffee fs-5"></i>
                <h6 class="fw-bold mb-0 text-coffee">Pesanan Pelanggan</h6>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-dark text-white border" id="cartItemsCount">0 Item</span>
                <button type="button" class="btn btn-sm btn-outline-danger" id="btnClearCart" title="Kosongkan Keranjang">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        </div>

        <!-- Cart Items List -->
        <div class="cart-items-container">
            <div id="cartEmptyState" class="text-center py-5">
                <i class="bi bi-cart-x fs-1 text-muted"></i>
                <p class="mt-2 text-muted fw-semibold mb-0">Keranjang masih kosong</p>
                <p class="text-muted small">Pilih menu di sebelah kiri untuk memulai pesanan.</p>
            </div>
            <div id="cartItemsList">
                <!-- Cart item rows inserted here by pos.js -->
            </div>
        </div>

        <!-- Cart Summary & Checkout Button -->
        <div class="cart-summary-box">
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Subtotal</span>
                <span class="fw-semibold text-dark" id="cartSubtotal">Rp 0</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
                <span class="text-muted">Pajak (11%)</span>
                <span class="fw-semibold text-dark" id="cartTax">Rp 0</span>
            </div>
            <hr class="my-2 border-secondary border-opacity-25">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <span class="fw-bold fs-6 text-coffee">Total Pembayaran</span>
                <span class="fw-bold fs-5 text-coffee" id="cartTotal">Rp 0</span>
            </div>

            <button type="button" class="btn btn-coffee w-100 py-2 fw-bold d-flex align-items-center justify-content-center gap-2" id="btnOpenCheckout" disabled>
                <i class="bi bi-credit-card-2-front"></i>
                <span>Bayar Pesanan</span>
            </button>
        </div>
    </aside>
</div>

<!-- Modal Pembayaran & Checkout -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-coffee text-white">
                <h5 class="modal-title fw-bold" id="checkoutModalLabel">
                    <i class="bi bi-wallet-fill me-2 text-warning"></i>Pembayaran Transaksi
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Ringkasan Pembayaran Table -->
                <div class="card border mb-4 bg-light">
                    <div class="card-header bg-white py-2 border-bottom">
                        <span class="fw-bold text-coffee small text-uppercase"><i class="bi bi-receipt me-1"></i> Ringkasan Pembayaran</span>
                    </div>
                    <div class="card-body p-3">
                        <table class="table table-sm table-borderless mb-0">
                            <tbody>
                                <tr>
                                    <td class="text-muted">Subtotal</td>
                                    <td class="text-end fw-semibold" id="modalSubtotalAmount">Rp 0</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Pajak (11%)</td>
                                    <td class="text-end fw-semibold" id="modalTaxAmount">Rp 0</td>
                                </tr>
                                <tr class="border-top">
                                    <td class="fw-bold text-coffee fs-6 pt-2">Total Pembayaran</td>
                                    <td class="text-end fw-bold text-coffee fs-5 pt-2" id="modalTotalAmount">Rp 0</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Payment Method Options -->
                <div class="mb-4">
                    <label class="form-label fw-bold text-dark small text-uppercase">Pilih Metode Pembayaran</label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="payment_method_option" id="payMethodCash" value="cash" checked>
                            <label class="btn btn-outline-coffee w-100 py-3 d-flex flex-column align-items-center justify-content-center" for="payMethodCash">
                                <i class="bi bi-cash-stack fs-3 mb-1"></i>
                                <span class="fw-bold">Cash</span>
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="payment_method_option" id="payMethodQris" value="qris">
                            <label class="btn btn-outline-coffee w-100 py-3 d-flex flex-column align-items-center justify-content-center" for="payMethodQris">
                                <i class="bi bi-qr-code-scan fs-3 mb-1"></i>
                                <span class="fw-bold">QRIS</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- QRIS Notice Section -->
                <div id="qrisPaymentSection" style="display: none;" class="p-3 bg-light rounded-3 text-center border mb-3">
                    <i class="bi bi-qr-code text-coffee" style="font-size: 3.5rem;"></i>
                    <p class="fw-bold text-coffee mb-1 mt-2">Scan QRIS Pelanggan</p>
                    <p class="small text-muted mb-0">Pembayaran langsung diproses otomatis tanpa uang kembalian.</p>
                </div>

                <!-- Cash Payment Section -->
                <div id="cashPaymentSection">
                    <div class="mb-3">
                        <label for="cashAmountInput" class="form-label fw-semibold">Jumlah Bayar (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light fw-bold text-muted">Rp</span>
                            <input type="number" id="cashAmountInput" class="form-control fw-bold fs-4 text-coffee" min="0" step="1000" placeholder="0">
                        </div>
                        <div id="cashValidationMsg" class="text-danger small mt-1 fw-semibold" style="display: none;">
                            <i class="bi bi-exclamation-circle me-1"></i> Jumlah pembayaran tidak mencukupi.
                        </div>
                    </div>

                    <!-- Quick Cash Amounts -->
                    <div class="mb-3">
                        <span class="text-muted small fw-semibold d-block mb-1">Pilihan Nominal Cepat:</span>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-quick-cash" data-val="exact">Uang Pas</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-quick-cash" data-val="50000">50.000</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-quick-cash" data-val="100000">100.000</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-quick-cash" data-val="150000">150.000</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary btn-quick-cash" data-val="200000">200.000</button>
                        </div>
                    </div>

                    <!-- Kembalian Display -->
                    <div class="p-3 bg-light rounded-3 d-flex justify-content-between align-items-center border">
                        <span class="fw-semibold text-secondary">Kembalian:</span>
                        <span class="fw-bold fs-5 text-success" id="cashChangeAmount">Rp 0</span>
                    </div>
                </div>
            </div>

            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-coffee px-4" id="btnSubmitPayment">
                    <i class="bi bi-check-circle me-1"></i> Proses Bayar & Selesai
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/pos.js') }}"></script>
@endpush
