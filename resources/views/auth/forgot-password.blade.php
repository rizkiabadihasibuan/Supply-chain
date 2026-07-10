<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SupplyRisk.io</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom-dark.css') }}">
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100 py-5" style="background-color: var(--bg-primary);">

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">

                <!-- Success Alert -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show glass-card border-success text-success d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="glass-card p-5">
                    <div class="text-center mb-4">
                        <a href="{{ route('landing') }}" class="text-decoration-none">
                            <span class="fs-2 fw-bold text-glow-cyan">SupplyRisk.io</span>
                        </a>
                        <h4 class="text-white mt-3">Lupa Password?</h4>
                        <p class="text-secondary small">Masukkan email Anda dan kami akan mengirimkan link untuk mengatur ulang password Anda</p>
                    </div>

                    <form action="{{ route('password.email') }}" method="POST">
                        @csrf

                        <!-- Email Input -->
                        <div class="mb-4">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary bg-transparent text-secondary border-opacity-25" style="border-right: none !important;"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@perusahaan.com" value="{{ old('email') }}" style="border-left: none !important; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3 fs-6">Kirim Link Reset</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-secondary small mb-0"><a href="{{ route('login') }}" class="text-glow-cyan text-decoration-none"><i class="bi bi-arrow-left me-1"></i> Kembali ke Halaman Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle with Popper JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
