/**
 * el'sCoffe POS - Point of Sale Interactive Terminal
 */

document.addEventListener('DOMContentLoaded', function () {
  // Cart State
  let cart = [];
  let selectedCategory = 'all';
  let searchQuery = '';
  let selectedPaymentMethod = 'cash';

  // DOM Elements
  const productsContainer = document.getElementById('posProductsGrid');
  const searchInput = document.getElementById('posSearchInput');
  const categoryButtons = document.querySelectorAll('.category-pill-btn');
  const cartItemsContainer = document.getElementById('cartItemsList');
  const cartEmptyState = document.getElementById('cartEmptyState');
  const cartSubtotalEl = document.getElementById('cartSubtotal');
  const cartTaxEl = document.getElementById('cartTax');
  const cartTotalEl = document.getElementById('cartTotal');
  const cartItemsCountEl = document.getElementById('cartItemsCount');
  const checkoutBtn = document.getElementById('btnOpenCheckout');
  const clearCartBtn = document.getElementById('btnClearCart');

  // Checkout Modal Elements
  const checkoutModalEl = document.getElementById('checkoutModal');
  const checkoutModal = checkoutModalEl ? new bootstrap.Modal(checkoutModalEl) : null;
  const modalSubtotalAmountEl = document.getElementById('modalSubtotalAmount');
  const modalTaxAmountEl = document.getElementById('modalTaxAmount');
  const modalTotalAmountEl = document.getElementById('modalTotalAmount');
  const cashSection = document.getElementById('cashPaymentSection');
  const qrisSection = document.getElementById('qrisPaymentSection');
  const cashAmountInput = document.getElementById('cashAmountInput');
  const cashChangeEl = document.getElementById('cashChangeAmount');
  const cashValidationMsg = document.getElementById('cashValidationMsg');
  const submitPaymentBtn = document.getElementById('btnSubmitPayment');
  const paymentMethodInputs = document.querySelectorAll('input[name="payment_method_option"]');

  // Receipt Modal Elements
  const receiptModalEl = document.getElementById('receiptModal');
  const receiptModal = receiptModalEl ? new bootstrap.Modal(receiptModalEl) : null;
  const receiptContentEl = document.getElementById('receiptModalContent');
  const printReceiptBtn = document.getElementById('btnPrintReceipt');

  // Fetch Products via AJAX
  function fetchProducts() {
    const url = new URL('/pos/products', window.location.origin);
    if (selectedCategory && selectedCategory !== 'all') {
      url.searchParams.append('category_id', selectedCategory);
    }
    if (searchQuery.trim() !== '') {
      url.searchParams.append('search', searchQuery.trim());
    }

    productsContainer.innerHTML = `
      <div class="col-12 text-center py-5">
        <div class="spinner-border text-warning" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">Memuat daftar menu...</p>
      </div>
    `;

    fetch(url, {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
      }
    })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          renderProducts(data.products);
        }
      })
      .catch(error => {
        console.error('Error fetching products:', error);
        productsContainer.innerHTML = `
          <div class="col-12 text-center py-5 text-danger">
            <i class="bi bi-exclamation-triangle fs-1"></i>
            <p class="mt-2">Gagal memuat produk. Silakan coba lagi.</p>
          </div>
        `;
      });
  }

  // Render Products Grid
  function renderProducts(products) {
    if (!products || products.length === 0) {
      productsContainer.innerHTML = `
        <div class="col-12 text-center py-5">
          <i class="bi bi-cup-hot fs-1 text-muted"></i>
          <h5 class="mt-3 text-muted">Menu tidak ditemukan</h5>
          <p class="text-muted small">Coba cari dengan kata kunci lain atau pilih kategori berbeda.</p>
        </div>
      `;
      return;
    }

    productsContainer.innerHTML = products.map(product => {
      const isSoldOut = product.product_stock <= 0;
      return `
        <div class="col-6 col-md-4 col-xl-3 mb-3">
          <div class="product-pos-card ${isSoldOut ? 'sold-out' : ''}" 
               data-id="${product.id}" 
               data-name="${escapeHtml(product.product_name)}" 
               data-price="${product.product_price}" 
               data-stock="${product.product_stock}">
            <div class="product-pos-img-wrapper">
              <img src="${product.product_photo}" alt="${escapeHtml(product.product_name)}" class="product-pos-img" loading="lazy">
              ${isSoldOut ? '<span class="sold-out-badge">SOLD OUT</span>' : ''}
            </div>
            <div class="p-2 d-flex flex-column flex-grow-1">
              <span class="badge bg-light text-dark align-self-start border mb-1 small">${escapeHtml(product.category_name)}</span>
              <h6 class="card-title fw-bold mb-1 text-truncate" title="${escapeHtml(product.product_name)}">
                ${escapeHtml(product.product_name)}
              </h6>
              <div class="mt-auto d-flex align-items-center justify-content-between pt-1">
                <span class="fw-bold text-coffee">${product.formatted_price}</span>
                <span class="badge ${product.badge_class} small">
                  ${isSoldOut ? '0' : product.product_stock}
                </span>
              </div>
            </div>
          </div>
        </div>
      `;
    }).join('');

    // Attach click event to product cards
    document.querySelectorAll('.product-pos-card').forEach(card => {
      card.addEventListener('click', function () {
        if (this.classList.contains('sold-out')) {
          Swal.fire({
            icon: 'warning',
            title: 'Stok Habis',
            text: 'Produk ini sedang sold out dan tidak dapat dipesan.',
            confirmButtonColor: '#3E2723'
          });
          return;
        }

        const id = parseInt(this.getAttribute('data-id'));
        const name = this.getAttribute('data-name');
        const price = parseFloat(this.getAttribute('data-price'));
        const stock = parseInt(this.getAttribute('data-stock'));

        addToCart({ id, name, price, stock });
      });
    });
  }

  // Add Item to Cart
  function addToCart(item) {
    const existingIndex = cart.findIndex(c => c.id === item.id);

    if (existingIndex > -1) {
      // Check stock limit
      if (cart[existingIndex].quantity + 1 > item.stock) {
        Swal.fire({
          icon: 'warning',
          title: 'Stok Tidak Mencukupi',
          text: `Stok maksimal untuk ${item.name} adalah ${item.stock}.`,
          confirmButtonColor: '#3E2723'
        });
        return;
      }
      cart[existingIndex].quantity += 1;
    } else {
      if (item.stock < 1) {
        Swal.fire({
          icon: 'warning',
          title: 'Stok Habis',
          text: 'Produk ini sudah tidak memiliki stok.',
          confirmButtonColor: '#3E2723'
        });
        return;
      }
      cart.push({
        id: item.id,
        name: item.name,
        price: item.price,
        stock: item.stock,
        quantity: 1
      });
    }

    renderCart();
  }

  // Update Item Quantity
  function updateQuantity(id, delta) {
    const index = cart.findIndex(c => c.id === id);
    if (index === -1) return;

    const newQty = cart[index].quantity + delta;

    if (newQty <= 0) {
      removeItem(id);
      return;
    }

    if (newQty > cart[index].stock) {
      Swal.fire({
        icon: 'warning',
        title: 'Stok Tidak Mencukupi',
        text: `Maksimal pembelian untuk ${cart[index].name} adalah ${cart[index].stock}.`,
        confirmButtonColor: '#3E2723'
      });
      return;
    }

    cart[index].quantity = newQty;
    renderCart();
  }

  // Remove Item from Cart
  function removeItem(id) {
    cart = cart.filter(c => c.id !== id);
    renderCart();
  }

  // Render Cart UI
  function renderCart() {
    if (cart.length === 0) {
      cartEmptyState.style.display = 'block';
      cartItemsContainer.innerHTML = '';
      cartSubtotalEl.textContent = 'Rp 0';
      if (cartTaxEl) cartTaxEl.textContent = 'Rp 0';
      cartTotalEl.textContent = 'Rp 0';
      cartItemsCountEl.textContent = '0 Item';
      checkoutBtn.disabled = true;
      return;
    }

    cartEmptyState.style.display = 'none';
    let totalItems = 0;

    cartItemsContainer.innerHTML = cart.map(item => {
      const itemSubtotal = item.price * item.quantity;
      totalItems += item.quantity;

      return `
        <div class="cart-item-row">
          <div style="flex-grow: 1; min-width: 0; padding-right: 0.5rem;">
            <div class="fw-bold text-truncate" title="${escapeHtml(item.name)}">${escapeHtml(item.name)}</div>
            <div class="text-muted small">${formatRupiah(item.price)} x ${item.quantity} = <span class="fw-semibold text-dark">${formatRupiah(itemSubtotal)}</span></div>
          </div>
          <div class="d-flex align-items-center gap-1">
            <button type="button" class="btn btn-sm btn-outline-secondary qty-btn btn-qty-minus" data-id="${item.id}">
              <i class="bi bi-dash"></i>
            </button>
            <span class="fw-bold px-1" style="min-width: 24px; text-align: center;">${item.quantity}</span>
            <button type="button" class="btn btn-sm btn-outline-secondary qty-btn btn-qty-plus" data-id="${item.id}">
              <i class="bi bi-plus"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger qty-btn ms-1 btn-item-remove" data-id="${item.id}" title="Hapus">
              <i class="bi bi-trash"></i>
            </button>
          </div>
        </div>
      `;
    }).join('');

    const subtotal = getCartSubtotal();
    const tax = getCartTax();
    const total = getCartTotal();

    cartSubtotalEl.textContent = formatRupiah(subtotal);
    if (cartTaxEl) cartTaxEl.textContent = formatRupiah(tax);
    cartTotalEl.textContent = formatRupiah(total);
    cartItemsCountEl.textContent = `${totalItems} Item`;
    checkoutBtn.disabled = false;

    // Attach listeners for cart actions
    document.querySelectorAll('.btn-qty-minus').forEach(btn => {
      btn.addEventListener('click', () => updateQuantity(parseInt(btn.getAttribute('data-id')), -1));
    });

    document.querySelectorAll('.btn-qty-plus').forEach(btn => {
      btn.addEventListener('click', () => updateQuantity(parseInt(btn.getAttribute('data-id')), 1));
    });

    document.querySelectorAll('.btn-item-remove').forEach(btn => {
      btn.addEventListener('click', () => removeItem(parseInt(btn.getAttribute('data-id'))));
    });
  }

  // Calculate Subtotal (before tax)
  function getCartSubtotal() {
    return cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
  }

  // Calculate 11% Tax
  function getCartTax() {
    return Math.round(getCartSubtotal() * 0.11);
  }

  // Calculate Total (Subtotal + Tax)
  function getCartTotal() {
    return getCartSubtotal() + getCartTax();
  }

  // Clear Cart
  if (clearCartBtn) {
    clearCartBtn.addEventListener('click', function () {
      if (cart.length === 0) return;
      Swal.fire({
        title: 'Kosongkan Keranjang?',
        text: 'Semua item dalam pesanan akan dihapus.',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3E2723',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Kosongkan'
      }).then(result => {
        if (result.isConfirmed) {
          cart = [];
          renderCart();
        }
      });
    });
  }

  // Category Filter Click
  categoryButtons.forEach(button => {
    button.addEventListener('click', function () {
      categoryButtons.forEach(btn => btn.classList.remove('active'));
      this.classList.add('active');
      selectedCategory = this.getAttribute('data-category');
      fetchProducts();
    });
  });

  // Search Input with Debounce
  let searchTimeout = null;
  if (searchInput) {
    searchInput.addEventListener('input', function () {
      clearTimeout(searchTimeout);
      searchTimeout = setTimeout(() => {
        searchQuery = this.value;
        fetchProducts();
      }, 300);
    });
  }

  // Open Checkout Modal
  if (checkoutBtn) {
    checkoutBtn.addEventListener('click', function () {
      if (cart.length === 0) return;
      const subtotal = getCartSubtotal();
      const tax = getCartTax();
      const total = getCartTotal();

      if (modalSubtotalAmountEl) modalSubtotalAmountEl.textContent = formatRupiah(subtotal);
      if (modalTaxAmountEl) modalTaxAmountEl.textContent = formatRupiah(tax);
      modalTotalAmountEl.textContent = formatRupiah(total);

      // Reset payment inputs
      selectedPaymentMethod = 'cash';
      document.getElementById('payMethodCash').checked = true;
      cashSection.style.display = 'block';
      if (qrisSection) qrisSection.style.display = 'none';
      cashAmountInput.value = total; // default to exact amount
      updateCashCalculation();

      checkoutModal.show();
    });
  }

  // Payment Method Selection Toggle
  paymentMethodInputs.forEach(input => {
    input.addEventListener('change', function () {
      selectedPaymentMethod = this.value;
      if (selectedPaymentMethod === 'cash') {
        cashSection.style.display = 'block';
        if (qrisSection) qrisSection.style.display = 'none';
        updateCashCalculation();
      } else if (selectedPaymentMethod === 'qris') {
        cashSection.style.display = 'none';
        if (qrisSection) qrisSection.style.display = 'block';
        submitPaymentBtn.disabled = false;
        cashValidationMsg.style.display = 'none';
      } else {
        cashSection.style.display = 'none';
        if (qrisSection) qrisSection.style.display = 'none';
        submitPaymentBtn.disabled = false;
        cashValidationMsg.style.display = 'none';
      }
    });
  });

  // Quick Cash Amount Buttons
  document.querySelectorAll('.btn-quick-cash').forEach(btn => {
    btn.addEventListener('click', function () {
      const type = this.getAttribute('data-val');
      const total = getCartTotal();
      if (type === 'exact') {
        cashAmountInput.value = total;
      } else {
        cashAmountInput.value = parseInt(type);
      }
      updateCashCalculation();
    });
  });

  // Cash Amount Input Change
  if (cashAmountInput) {
    cashAmountInput.addEventListener('input', updateCashCalculation);
  }

  function updateCashCalculation() {
    if (selectedPaymentMethod !== 'cash') return;

    const total = getCartTotal();
    const paid = parseFloat(cashAmountInput.value) || 0;

    if (paid < total) {
      cashChangeEl.textContent = 'Rp 0';
      cashValidationMsg.style.display = 'block';
      submitPaymentBtn.disabled = true;
    } else {
      const change = paid - total;
      cashChangeEl.textContent = formatRupiah(change);
      cashValidationMsg.style.display = 'none';
      submitPaymentBtn.disabled = false;
    }
  }

  // Submit Checkout
  if (submitPaymentBtn) {
    submitPaymentBtn.addEventListener('click', function () {
      const total = getCartTotal();
      const paid = selectedPaymentMethod === 'cash' 
        ? (parseFloat(cashAmountInput.value) || 0) 
        : total;

      if (selectedPaymentMethod === 'cash' && paid < total) {
        Swal.fire({
          icon: 'error',
          title: 'Pembayaran Kurang',
          text: 'Jumlah pembayaran tidak mencukupi.',
          confirmButtonColor: '#3E2723'
        });
        return;
      }

      // Prepare payload
      const payload = {
        payment_method: selectedPaymentMethod,
        order_paid: paid,
        items: cart.map(item => ({
          product_id: item.id,
          quantity: item.quantity
        }))
      };

      // Disable button during process
      submitPaymentBtn.disabled = true;
      submitPaymentBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Memproses...';

      const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

      fetch('/pos/checkout', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify(payload)
      })
        .then(response => response.json().then(data => ({ status: response.status, data })))
        .then(({ status, data }) => {
          submitPaymentBtn.disabled = false;
          submitPaymentBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Proses Bayar & Selesai';

          if (data.success) {
            checkoutModal.hide();

            // Show SweetAlert Success
            Swal.fire({
              icon: 'success',
              title: 'Transaksi Berhasil!',
              html: `
                <p class="mb-1">Invoice: <strong>${data.order_code}</strong></p>
                <p class="mb-1">Subtotal: <strong>${formatRupiah(data.subtotal)}</strong></p>
                <p class="mb-1">Pajak (11%): <strong>${formatRupiah(data.tax)}</strong></p>
                <p class="mb-1">Total Pembayaran: <strong>${formatRupiah(data.total)}</strong></p>
                <p class="mb-0">Kembalian: <strong>${formatRupiah(data.change)}</strong></p>
              `,
              showCancelButton: true,
              confirmButtonColor: '#3E2723',
              cancelButtonColor: '#C49A6C',
              confirmButtonText: '<i class="bi bi-printer me-1"></i> Cetak Struk',
              cancelButtonText: 'Tutup & Transaksi Baru'
            }).then(result => {
              if (result.isConfirmed) {
                // Open receipt window for direct print
                window.open(data.receipt_url, '_blank', 'width=450,height=600');
              }
            });

            // Reset cart & reload products to reflect decreased stocks
            cart = [];
            renderCart();
            fetchProducts();
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Transaksi Gagal',
              text: data.message || 'Transaksi gagal diproses.',
              confirmButtonColor: '#3E2723'
            });
          }
        })
        .catch(err => {
          console.error('Checkout error:', err);
          submitPaymentBtn.disabled = false;
          submitPaymentBtn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Proses Bayar & Selesai';

          Swal.fire({
            icon: 'error',
            title: 'Kesalahan Jaringan',
            text: 'Terjadi gangguan saat memproses transaksi.',
            confirmButtonColor: '#3E2723'
          });
        });
    });
  }

  // HTML escaping helper
  function escapeHtml(str) {
    if (!str) return '';
    return str
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#039;');
  }

  // Initial Load
  fetchProducts();
});
