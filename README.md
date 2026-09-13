# el'sCoffe - Point of Sale (POS) Web Application

Aplikasi web **Point of Sale (POS)** modern untuk coffee shop bernama **"el'sCoffe"** yang dibangun menggunakan **Laravel**, **MySQL**, **Bootstrap 5**, **Bootstrap Icons**, **Vanilla JavaScript**, **Chart.js**, dan **SweetAlert2**.

Sistem ini didesain sesuai ERD 6 tabel relasional yang bersih tanpa tabel tambahan yang tidak perlu, dilengkapi manajemen stok real-time, cetak struk kasir termal (*thermal receipt*), dasbor analitik penjualan interaktif, serta sistem otorisasi peran (*Admin* & *Kasir*).

---

## ☕ Fitur Utama

1. **Autentikasi & Otorisasi Berbasis Peran (Role Middleware)**:
   - **Admin**: Akses penuh ke seluruh sistem (Dashboard, POS, Produk, Kategori, Transaksi, dan Manajemen User).
   - **Kasir**: Akses ke Dashboard, POS, Produk, Kategori, dan Riwayat Transaksi. Dibatasi secara ketat dari Manajemen User (`/users` diblokir HTTP 403 Forbidden).

2. **Terminal Kasir POS Interaktif (Single Page Experience)**:
   - Pencarian produk instan secara *realtime* (AJAX / Fetch API).
   - Filter cepat berdasarkan kategori menu (*Coffee, Non Coffee, Tea, Food, Snack*).
   - Indikator stok cerdas (Hijau: stok aman >5, Kuning: stok menipis &le;5, Merah: **SOLD OUT** / 0).
   - Keranjang pesanan dinamis: tambah item, ubah kuantitas, validasi batas stok, hapus item, serta kalkulasi subtotal dan total otomatis tanpa reload halaman.
   - Pilihan metode pembayaran: **Cash**, **QRIS**, **Debit**, dan **E-Wallet**.
   - Khusus pembayaran tunai (*Cash*): tombol uang pas dan pecahan cepat (Rp20.000, Rp50.000, Rp100.000, Rp200.000), validasi nominal kurang, dan perhitungan uang kembalian otomatis.
   - Checkout menggunakan **Database Transaction (`DB::transaction`)** dengan *pessimistic row locking* untuk memastikan konsistensi stok inventaris.

3. **Cetak Struk Kasir (Thermal Receipt)**:
   - Format struk belanja khas kedai kopi (*el'sCoffe - Coffee & Good Mood*).
   - Layout khusus cetak printer kasir termal 58mm / 80mm dengan CSS `@media print`.
   - Mendukung cetak otomatis via `window.print()`.

4. **Dashboard & Analitik Penjualan**:
   - Total produk, total kategori, transaksi hari ini, omzet hari ini, omzet bulan ini, dan peringatan stok rendah.
   - Grafik garis tren penjualan 7 hari terakhir (**Chart.js**).
   - Grafik donat 5 menu kopi/makanan terlaris (**Chart.js**).
   - Grafik batang omzet penjualan 12 bulan pada tahun berjalan (**Chart.js**).
   - Tabel 8 transaksi terkini dengan status real-time.

5. **Manajemen Produk & Kategori (CRUD)**:
   - Upload foto produk ke Laravel Storage dengan kompresi dan preview langsung.
   - Proteksi relasi: Kategori tidak dapat dihapus jika masih digunakan oleh produk menu (*"Kategori masih digunakan oleh produk."*).
   - Filter produk berdasarkan nama, kategori, dan status aktif.

6. **Riwayat Transaksi & Pembatalan**:
   - Filter transaksi berdasarkan tanggal, metode pembayaran, status, dan pencarian nomor invoice.
   - Fitur pembatalan pesanan (*Cancel Order*) yang secara otomatis mengembalikan stok produk ke inventaris (*Restock*).

---

## 🗄️ Struktur Database (ERD 6 Tabel)

Aplikasi ini menggunakan tepat 6 tabel utama sesuai spesifikasi:

1. **`roles`**: `id`, `name`, `created_at`, `updated_at`
2. **`users`**: `id`, `role_id` (FK ke `roles.id`), `name`, `email`, `password`, `remember_token`, timestamps
3. **`categories`**: `id`, `category_name`, timestamps
4. **`products`**: `id`, `category_id` (FK ke `categories.id`), `product_name`, `product_photo`, `product_price`, `product_description`, `product_stock`, `is_active`, timestamps
5. **`orders`**: `id`, `user_id` (FK ke `users.id`), `order_code` (unique), `order_date`, `order_amount`, `order_paid`, `order_change`, `payment_method`, `order_status`, timestamps
6. **`order_details`**: `id`, `order_id` (FK ke `orders.id`), `product_id` (FK ke `products.id`), `order_quantity`, `order_price`, `order_subtotal`, timestamps

---

## 📋 Persyaratan Sistem (Requirements)

- **PHP** >= 8.2 (Ekstensi `pdo_mysql`, `mbstring`, `openssl`, `curl` aktif)
- **MySQL / MariaDB** (XAMPP / Laragon / Native MySQL)
- **Composer** >= 2.x
- Browser modern (Chrome, Edge, Firefox, Safari)

---

## 🚀 Panduan Instalasi & Menjalankan Aplikasi

### 1. Clone atau Buka Folder Project
```bash
cd "c:\xampp\htdocs\POS el'sCoffe"
```

### 2. Install Dependensi Composer
```bash
composer install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file konfigurasi jika belum ada:
```bash
copy .env.example .env
```
Pastikan pengaturan database di `.env` sudah sesuai:
```env
APP_NAME="el'sCoffe"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=elscoffee
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

### 4. Generate Application Key
```bash
php artisan key:generate
```

### 5. Buat Database MySQL
Buat database bernama `elscoffee` melalui phpMyAdmin atau terminal MySQL:
```sql
CREATE DATABASE IF NOT EXISTS elscoffee CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Jalankan Migrasi & Seeder Database
```bash
php artisan migrate:fresh --seed
```

### 7. Hubungkan Storage Publik
```bash
php artisan storage:link
```

### 8. Jalankan Server Aplikasi
```bash
php artisan serve
```
Aplikasi kini dapat diakses melalui peramban di:
👉 **[http://localhost:8000](http://localhost:8000)** atau **[http://127.0.0.1:8000](http://127.0.0.1:8000)**

---

## 🔑 Akun Login Bawaan (Default Credentials)

Tersedia tombol pengisian cepat (*One-Click Demo Filler*) pada halaman `/login`:

### 👑 Administrator (Ujicoba Utama)
- **Email**: `admin@gmail.com`
- **Password**: `12345678`
- **Hak Akses**: Mengakses seluruh menu sistem, termasuk Manajemen Pengguna.

*(Alternatif: `admin@elscoffee.test` / password `12345678`)*

### ☕ Kasir
- **Email**: `kasir@gmail.com` *(atau `kasir@elscoffee.test`)*
- **Password**: `12345678`
- **Hak Akses**: Dashboard, POS Terminal, Katalog Produk, Kategori, dan Riwayat Transaksi.

---

## 🧪 Menjalankan Pengujian Otomatis (Automated Testing)

Jalankan seluruh rangkaian pengujian fitur:
```bash
php artisan test --filter PosSystemTest
```

Pengujian mencakup:
- Aksesibilitas dan form otentikasi login.
- Otorisasi hak akses peran (Kasir dilarang mengakses `/users`, Admin diizinkan).
- Dashboard dinamis & agregasi metrik.
- Validasi CRUD Kategori (pencegahan penghapusan jika kategori masih memiliki produk menu).
- Validasi CRUD Produk (upload foto, harga numeric, stok non-negatif).
- Endpoint katalog AJAX kasir.
- Transaksi pembayaran tunai, kalkulasi uang kembalian, pemotongan stok otomatis, dan pembuatan invoice.
- Validasi penolakan pembayaran kurang & kuantitas melebihi stok.
- Pembatalan transaksi & restorasi stok (*stock rollback*).
- Tampilan detail transaksi dan cetak struk kasir (*thermal receipt*).

---

## 🎨 Palet Warna Identitas Kedai Kopi (Theme Palette)

- **Dark Brown**: `#3E2723`
- **Coffee**: `#6F4E37`
- **Cream**: `#F5E6D3`
- **Beige**: `#EAD7C0`
- **White**: `#FFFFFF`
- **Gold**: `#C49A6C`
