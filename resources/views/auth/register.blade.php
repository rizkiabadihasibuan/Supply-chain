<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru - SupplyRisk.io</title>
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
                        <h4 class="text-white mt-3">Buat Akun Baru</h4>
                        <p class="text-secondary small">Daftarkan akun operasional Anda untuk mengelola risiko logistik</p>
                    </div>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <!-- Name Input -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary bg-transparent text-secondary border-opacity-25" style="border-right: none !important;"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" placeholder="John Doe" value="{{ old('name') }}" style="border-left: none !important; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Email Input -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Alamat Email</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary bg-transparent text-secondary border-opacity-25" style="border-right: none !important;"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" placeholder="nama@perusahaan.com" value="{{ old('email') }}" style="border-left: none !important; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Selection -->
                        <div class="mb-3">
                            <label for="role_id" class="form-label">Peran Pengguna (Role)</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary bg-transparent text-secondary border-opacity-25" style="border-right: none !important;"><i class="bi bi-shield-check"></i></span>
                                <select name="role_id" id="role_id" class="form-select @error('role_id') is-invalid @enderror" style="border-left: none !important; border-top-left-radius: 0; border-bottom-left-radius: 0; background-color: rgba(17, 24, 39, 0.8); color: var(--text-primary);">
                                    <option value="" disabled selected>Pilih peran...</option>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }} - {{ $role->description }}</option>
                                    @endforeach
                                </select>
                                @error('role_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Password Input -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
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
                            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                            <div class="input-group">
                                <span class="input-group-text border-secondary bg-transparent text-secondary border-opacity-25" style="border-right: none !important;"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="••••••••" style="border-left: none !important; border-top-left-radius: 0; border-bottom-left-radius: 0;">
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" class="btn btn-primary w-100 py-2.5 mb-3 fs-6">Daftar Akun</button>
                    </form>

                    <div class="text-center mt-3">
                        <p class="text-secondary small mb-0">Sudah memiliki akun? <a href="{{ route('login') }}" class="text-glow-cyan text-decoration-none">Masuk di Sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle with Popper JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
