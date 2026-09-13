/**
 * el'sCoffe POS - Global Application JavaScript
 */

// Configure default SweetAlert2 Toast
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  didOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer);
    toast.addEventListener('mouseleave', Swal.resumeTimer);
  }
});

// Format Rupiah helper
function formatRupiah(number) {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0,
    maximumFractionDigits: 0
  }).format(number);
}

// Global SweetAlert Confirm for Delete Forms
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.btn-delete-confirm').forEach(function (button) {
    button.addEventListener('click', function (e) {
      e.preventDefault();
      const form = this.closest('form');
      const itemName = this.getAttribute('data-name') || 'data ini';

      Swal.fire({
        title: 'Konfirmasi Hapus',
        text: `Apakah Anda yakin ingin menghapus ${itemName}?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3E2723',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, Hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
});
