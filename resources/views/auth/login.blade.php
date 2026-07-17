<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - SupplyChain Platform</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
            --sidebar-bg: #123458;
            --background: #F8FAFC;
            --radius-custom: 16px;
        }
        body {
            background-color: var(--background);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
    </style>
</head>
<body>

<x-auth-card title="Masuk ke Platform" subtitle="Akses pusat kendali risiko rantai pasok global.">
    <div id="login-feedback-area" class="mb-3" style="display: none;">
        <x-validation-message type="success" message="Autentikasi berhasil! Mengalihkan ke dasbor..." />
    </div>

    <x-auth-form id="login-form" action="#">
        <x-auth-input label="Alamat Email" type="email" id="email" name="email" placeholder="nama@perusahaan.com" required="true" />
        
        <x-auth-input label="Kata Sandi" type="password" id="password" name="password" placeholder="••••••••" required="true">
            <x-password-toggle inputId="password" />
        </x-auth-input>

        <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: 0.825rem;">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember" style="width: 18px; height: 18px;">
                <label class="form-check-label text-secondary ms-1" for="remember">Ingat Saya</label>
            </div>
            <a href="{{ route('forgot-password') }}" class="text-primary text-decoration-none fw-medium">Lupa Sandi?</a>
        </div>

        <x-loading-button id="btn-login-submit" text="Masuk" loadingText="Memverifikasi Kredensial..." />
        
        <div class="text-center mt-3" style="font-size: 0.825rem;">
            <span class="text-secondary">Belum punya akun?</span>
            <a href="{{ route('register') }}" class="text-primary text-decoration-none fw-semibold ms-1">Daftar Sekarang</a>
        </div>

        <div class="position-relative my-4 text-center">
            <hr class="text-secondary opacity-25">
            <span class="position-absolute top-50 start-50 translate-middle px-3 bg-white text-secondary small">atau</span>
        </div>

        <!-- Google Login Placeholder (Disabled) -->
        <button type="button" class="btn btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-2" style="min-height: 44px; border-radius: 10px;" disabled title="Metode SSO Google dinonaktifkan sementara">
            <i class="bi bi-google text-danger"></i>
            <span class="small fw-semibold text-secondary">Masuk dengan Google</span>
        </button>
    </x-auth-form>
</x-auth-card>

<script>
    document.getElementById('login-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('btn-login-submit');
        const spinner = submitBtn.querySelector('.spinner-loading-icon');
        const label = submitBtn.querySelector('.button-label-text');
        
        // Trigger Loading state
        submitBtn.disabled = true;
        spinner.style.display = 'inline-block';
        label.textContent = 'Memverifikasi Kredensial...';
        
        setTimeout(() => {
            // Show Success feedback
            document.getElementById('login-feedback-area').style.display = 'block';
            
            setTimeout(() => {
                // Redirect flow: Login -> Dashboard
                window.location.href = '/dashboard';
            }, 1000);
        }, 1500);
    });
</script>
</body>
</html>
