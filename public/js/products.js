/**
 * el'sCoffe POS - Products Management JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {
  // Photo Preview Handler
  const photoInput = document.getElementById('product_photo_input');
  const previewImg = document.getElementById('product_photo_preview');

  if (photoInput && previewImg) {
    photoInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        // Validate file type
        const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/svg+xml'];
        if (!validTypes.includes(file.type)) {
          Swal.fire({
            icon: 'error',
            title: 'Format Tidak Didukung',
            text: 'Harap pilih file gambar (JPG, PNG, WEBP, atau SVG).',
            confirmButtonColor: '#3E2723'
          });
          this.value = '';
          return;
        }

        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
          Swal.fire({
            icon: 'error',
            title: 'File Terlalu Besar',
            text: 'Ukuran foto maksimal adalah 2MB.',
            confirmButtonColor: '#3E2723'
          });
          this.value = '';
          return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
          previewImg.src = e.target.result;
          previewImg.style.display = 'block';
        };
        reader.readAsDataURL(file);
      }
    });
  }
});
