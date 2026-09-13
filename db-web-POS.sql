-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 13 Sep 2026 pada 23.19
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `els_coffee`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `category_name`, `created_at`, `updated_at`) VALUES
(1, 'Coffee', '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(2, 'Non Coffee', '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(3, 'Tea', '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(4, 'Food', '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(5, 'Snack', '2026-09-11 00:28:01', '2026-09-11 00:28:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2026_01_01_000001_create_roles_table', 1),
(2, '2026_01_01_000002_create_users_table', 1),
(3, '2026_01_01_000003_create_categories_table', 1),
(4, '2026_01_01_000004_create_products_table', 1),
(5, '2026_01_01_000005_create_orders_table', 1),
(6, '2026_01_01_000006_create_order_details_table', 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(255) NOT NULL,
  `order_date` datetime NOT NULL,
  `order_subtotal` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `order_amount` decimal(12,2) NOT NULL,
  `order_paid` decimal(12,2) NOT NULL,
  `order_change` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_method` enum('cash','qris','debit','ewallet') NOT NULL,
  `order_status` enum('completed','cancelled') NOT NULL DEFAULT 'completed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_code`, `order_date`, `order_subtotal`, `order_tax`, `order_amount`, `order_paid`, `order_change`, `payment_method`, `order_status`, `created_at`, `updated_at`) VALUES
(18, 5, 'INV-20260910-0018', '2026-09-10 17:31:00', 48000.00, 5280.00, 53280.00, 53280.00, 0.00, 'qris', 'completed', '2026-09-11 00:28:02', '2026-09-11 00:28:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `order_details`
--

CREATE TABLE `order_details` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `order_quantity` int(11) NOT NULL,
  `order_price` decimal(12,2) NOT NULL,
  `order_subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `order_quantity`, `order_price`, `order_subtotal`, `created_at`, `updated_at`) VALUES
(37, 18, 10, 2, 24000.00, 48000.00, '2026-09-11 00:28:02', '2026-09-11 00:28:02');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_photo` varchar(255) DEFAULT NULL,
  `product_price` decimal(12,2) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_stock` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `product_name`, `product_photo`, `product_price`, `product_description`, `product_stock`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 'Americano', 'products/NkjVvYyfOCNQL4ej7x4HVQ8bGAngWGu6AN8R8mRG.webp', 20000.00, 'Rich espresso diluted with hot or cold water, clean and bold.', 32, 1, '2026-09-11 00:28:01', '2026-09-13 02:17:17'),
(4, 1, 'Cappuccino', NULL, 25000.00, 'Equal parts espresso, steamed milk, and velvety milk foam.', 17, 1, '2026-09-11 00:28:01', '2026-09-13 02:16:54'),
(5, 1, 'Caramel Macchiato', NULL, 28000.00, 'Steamed milk with vanilla syrup, marked with espresso and caramel drizzle.', 18, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(6, 1, 'Vanilla Latte', NULL, 27000.00, 'Espresso and steamed milk blended with aromatic Madagascar vanilla.', 15, 1, '2026-09-11 00:28:01', '2026-09-13 02:16:54'),
(7, 1, 'Mocha', NULL, 28000.00, 'Decadent chocolate sauce infused with espresso and fresh milk.', 4, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(9, 2, 'Matcha Latte', NULL, 26000.00, 'Authentic Uji Japanese matcha whisked with fresh milk.', 18, 1, '2026-09-11 00:28:01', '2026-09-13 02:16:54'),
(10, 2, 'Chocolate', NULL, 24000.00, 'Rich Belgian chocolate drink served hot or iced with cocoa dusting.', 22, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(15, 3, 'Lemon Iced Tea', NULL, 18000.00, 'Refreshing black tea with fresh lemon slices and mint leaves.', 20, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(16, 3, 'Peach Tea', NULL, 22000.00, 'Fruity artisan tea infused with sweet peach slices.', 16, 1, '2026-09-11 00:28:01', '2026-09-13 02:17:17'),
(17, 4, 'Croissant Butter', NULL, 22000.00, 'Flaky, buttery French pastry freshly baked daily.', 8, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(18, 4, 'Sandwich Club', NULL, 32000.00, 'Toasted bread loaded with smoked beef, cheddar, egg, and fresh greens.', 10, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(19, 4, 'Spaghetti Aglio Olio', NULL, 35000.00, 'Al dente spaghetti with garlic, olive oil, chilli flakes, and grilled chicken.', 12, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(20, 4, 'Beef Burger', NULL, 38000.00, 'Juicy Australian beef patty, melted cheddar, lettuce, caramelized onions.', 5, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(21, 5, 'French Fries', NULL, 20000.00, 'Crispy golden shoestring potatoes with sea salt and garlic mayo.', 30, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(22, 5, 'Chocolate Donut', NULL, 15000.00, 'Soft artisan donut topped with rich dark chocolate ganache.', 15, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01'),
(23, 5, 'Tiramisu Cake', NULL, 28000.00, 'Espresso-soaked ladyfingers layered with mascarpone cheese mousse.', 7, 1, '2026-09-11 00:28:01', '2026-09-11 00:28:01');

-- --------------------------------------------------------

--
-- Struktur dari tabel `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'admin', '2026-09-11 00:28:00', '2026-09-11 00:28:00'),
(2, 'kasir', '2026-09-11 00:28:00', '2026-09-11 00:28:00'),
(3, 'pimpinan', '2026-09-11 00:28:00', '2026-09-11 00:28:00');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin Zee', 'admin@gmail.com', '2026-09-11 00:28:00', '$2y$12$kOQlqTCJXIEiYrQ6fXjII.PZAuvt/dLVNVNqp3qx4hJC9fVI7sJx2', NULL, '2026-09-11 00:28:00', '2026-09-11 00:30:58'),
(3, 3, 'Lead Bambang', 'pimpinan@gmail.com', '2026-09-11 00:28:01', '$2y$12$ZoCm4El5l75FPKW2CVnoEuT9pRC171/QooJOtXP5mi/xaXXvo1gFq', NULL, '2026-09-11 00:28:01', '2026-09-11 00:31:09'),
(5, 2, 'Kasir Budi', 'kasir@gmail.com', '2026-09-11 00:28:01', '$2y$12$1ZeRdCuG3V8X5IFj91D3DOqNmQLXLJKTQ7FhdUsfWpY1yBB9iUeei', NULL, '2026-09-11 00:28:01', '2026-09-11 00:30:39');

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_user_id_index` (`user_id`),
  ADD KEY `orders_order_date_index` (`order_date`);

--
-- Indeks untuk tabel `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_details_order_id_index` (`order_id`),
  ADD KEY `order_details_product_id_index` (`product_id`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `products_category_id_index` (`category_id`);

--
-- Indeks untuk tabel `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indeks untuk tabel `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_index` (`role_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT untuk tabel `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT untuk tabel `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT untuk tabel `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT untuk tabel `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ketidakleluasaan untuk tabel `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_details_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Ketidakleluasaan untuk tabel `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
