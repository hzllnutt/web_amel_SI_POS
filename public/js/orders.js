/**
 * el'sCoffe POS - Orders Management JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
  // Print receipt buttons
  document.querySelectorAll('.btn-print-receipt').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const receiptUrl = this.getAttribute('data-receipt-url') || this.getAttribute('href');
      if (receiptUrl) {
        window.open(receiptUrl, '_blank', 'width=450,height=600');
      } else {
        window.print();
      }
    });
  });

  // Cancel order button confirmation
  document.querySelectorAll('.btn-cancel-order').forEach(btn => {
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      const form = this.closest('form');
      const orderCode = this.getAttribute('data-code') || 'transaksi ini';

      Swal.fire({
        title: 'Batalkan Transaksi?',
        text: `Apakah Anda yakin ingin membatalkan ${orderCode}? Stok produk akan dikembalikan ke inventaris.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Batalkan Transaksi',
        cancelButtonText: 'Kembali'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});
