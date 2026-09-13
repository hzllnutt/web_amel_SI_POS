<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | PPKD Cafe's</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: linear-gradient(135deg, #2B1B17 0%, #3E2723 50%, #6F4E37 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .login-card {
            background: #FFFFFF;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 440px;
            overflow: hidden;
            border: none;
        }
        .login-header {
            background: linear-gradient(135deg, #3E2723 0%, #6F4E37 100%);
            color: #FFFFFF;
            padding: 2.2rem 1.5rem;
            text-align: center;
        }
        .login-icon {
            width: 64px;
            height: 64px;
            background: #C49A6C;
            color: #3E2723;
            border-radius: 16px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 6px 15px rgba(196, 154, 108, 0.4);
            margin-bottom: 0.75rem;
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <div class="login-icon">
            <i class="bi bi-cup-hot-fill"></i>
        </div>
        <h3 class="fw-bold mb-1">PPKD <span style="color: #C49A6C;">Cafe's</span></h3>
        <p class="text-white-50 small mb-0">Point of Sales</p>
    </div>

    <div class="p-4 p-md-5">
        @if(session('error'))
            <div class="alert alert-danger d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <div class="small">{{ session('error') }}</div>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success d-flex align-items-center mb-3" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                <div class="small">{{ session('success') }}</div>
            </div>
        @endif

        <!-- Banner Info Akun Ujicoba -->
        {{-- <div class="alert alert-warning py-2 px-3 mb-3 small border-0" style="background-color: #F5E6D3; color: #3E2723;">
            <i class="bi bi-info-circle-fill me-1 text-coffee"></i> <strong>Akun Ujicoba:</strong><br>
            Email: <code>admin@gmail.com</code><br>
            Password: <code>12345678</code>
        </div> --}}

        <form action="{{ route('login.post') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold text-secondary small">Email</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" id="email"
                           class="form-control bg-light border-start-0 @error('email') is-invalid @enderror"
                           value="{{ old('email') }}"
                           placeholder="Enter Your Email" required>
                </div>
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label fw-semibold text-secondary small">Password</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="password" id="password"
                           class="form-control bg-light border-start-0 @error('password') is-invalid @enderror"
                           value=""
                           placeholder="Input Your Password" required>
                </div>
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>


            <button type="submit" class="btn btn-coffee w-100 py-2 fw-bold shadow-sm">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk Sekarang
            </button>
        </form>

        <!-- Quick Demo Credentials Box -->
        {{-- <div class="mt-4 pt-3 border-top">
            <p class="text-muted small fw-bold mb-2 text-center">Akun Ujicoba (Klik untuk isi cepat):</p>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="fillDemo('admin@gmail.com', 'password')">
                    <i class="bi bi-shield-lock me-1"></i> Admin
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="fillDemo('kasir@gmail.com', 'password')">
                    <i class="bi bi-person-badge me-1"></i> Kasir
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary flex-grow-1" onclick="fillDemo('pimpinan@gmail.com', 'password')">
                    <i class="bi bi-briefcase me-1"></i> Pimpinan
                </button>
            </div>
        </div> --}}
    </div>
</div>

<script>
function fillDemo(email, pass) {
    document.getElementById('email').value = email;
    document.getElementById('password').value = pass;
}
</script>

</body>
</html>
