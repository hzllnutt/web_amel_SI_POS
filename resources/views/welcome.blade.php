<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- Mengatur format karakter agar mendukung berbagai bahasa -->
    <meta charset="utf-8">

    <!-- Membuat tampilan website responsif di HP maupun laptop -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Menampilkan judul aplikasi dari file konfigurasi Laravel -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Memanggil font bawaan Laravel -->
    @fonts

    <!-- Memanggil file CSS dan JavaScript -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Jika file Vite belum dibuat, gunakan style bawaan Laravel -->
        <style>
            ...
        </style>
    @endif
</head>

<!-- Bagian body sebagai isi halaman website -->
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">

    <!-- Header halaman -->
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">

        <!-- Mengecek apakah halaman login tersedia -->
        @if (Route::has('login'))

            <!-- Menu navigasi -->
            <nav class="flex items-center justify-end gap-4">

                <!-- Jika pengguna sudah login -->
                @auth
                    <!-- Tampilkan tombol Dashboard -->
                    <a href="{{ url('/dashboard') }}">
                        Dashboard
                    </a>

                @else
                    <!-- Jika belum login, tampilkan tombol Login -->
                    <a href="{{ route('login') }}">
                        Log in
                    </a>

                    <!-- Jika fitur register aktif, tampilkan tombol Register -->
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}">
                            Register
                        </a>
                    @endif

                @endauth
            </nav>

        @endif
    </header>

    <!-- Konten utama halaman -->
    <div class="flex items-center justify-center w-full">

        <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">

            <!-- Bagian kiri berisi informasi awal Laravel -->
            <div class="flex-1 p-6 bg-white dark:bg-[#161615]">

                <!-- Judul halaman -->
                <h1>Let's get started</h1>

                <!-- Deskripsi singkat -->
                <p>
                    Laravel memberikan beberapa pilihan untuk mulai membuat aplikasi.
                </p>

                <!-- Daftar panduan yang disediakan Laravel -->
                <ul>

                    <li>
                        <!-- Link menuju dokumentasi Laravel -->
                        <a href="https://laravel.com/docs" target="_blank">
                            Documentation
                        </a>
                    </li>

                    <li>
                        <!-- Link menuju video pembelajaran Laracasts -->
                        <a href="https://laracasts.com" target="_blank">
                            Laracasts
                        </a>
                    </li>

                </ul>

                <!-- Tombol untuk melakukan deploy aplikasi Laravel -->
                <a href="https://cloud.laravel.com" target="_blank">
                    Deploy now
                </a>

                <!-- Menampilkan versi Laravel yang sedang digunakan -->
                <p>
                    v{{ app()->version() }}
                </p>

                <!-- Link untuk melihat riwayat perubahan Laravel -->
                <a href="https://github.com/laravel/framework/blob/13.x/CHANGELOG.md" target="_blank">
                    View changelog
                </a>

            </div>

            <!-- Bagian kanan hanya berisi ilustrasi/logo Laravel -->
            <div>
                <!-- SVG atau gambar logo Laravel -->
                ...
            </div>

        </main>
    </div>

</body>
</html>
