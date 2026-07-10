<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - SupplyRisk.io</title>
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
                <div class="glass-card p-5">
                    <div class="text-center mb-4">
                        <a href="{{ route('landing') }}" class="text-decoration-none">
                            <span class="fs-2 fw-bold text-glow-cyan">SupplyRisk.io</span>
                        </a>
                        <h4 class="text-white mt-3">Atur Ulang Password</h4>
                        <p class="text-secondary small">Masukkan email dan password baru Anda di bawah ini</p>
                    </div>

                    <form action="{{ route('password.update') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary bg-transparent text-secondary border-opacity-25" style="border-right: none !important;"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@perusahaan.com" value="{{ old('email', request()->email) }}" style="border-left: none !important; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary bg-transparent text-secondary border-opacity-25" style="border-right: none !important;"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••" style="border-left: none !important; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Confirmation Input -->
                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary bg-transparent text-secondary border-opacity-25" style="border-right: none !important;"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" style="border-left: none !important; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3 fs-6">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle with Popper JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
