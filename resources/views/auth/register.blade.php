<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - SupplyChain Platform</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
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

<x-auth-card title="Pendaftaran Akun" subtitle="Buat akun baru untuk memulai pemantauan rantai pasok.">
    <div id="register-feedback-area" class="mb-3" style="display: none;">
        <!-- Injected validation error or success -->
    </div>

    <x-auth-form id="register-form" action="#">
        <x-auth-input label="Nama Lengkap" type="text" id="fullname" name="fullname" placeholder="John Doe" required="true" />
        
        <x-auth-input label="Alamat Email" type="email" id="email" name="email" placeholder="nama@perusahaan.com" required="true" />
        
        <x-auth-input label="Kata Sandi" type="password" id="password" name="password" placeholder="••••••••" required="true">
            <x-password-toggle inputId="password" />
        </x-auth-input>

        <x-auth-input label="Konfirmasi Kata Sandi" type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required="true">
            <x-password-toggle inputId="password_confirmation" />
        </x-auth-input>

        <div class="mb-3" style="font-size: 0.825rem;">
            <div class="form-check">
                <input type="checkbox" class="form-check-input" id="terms" name="terms" required style="width: 18px; height: 18px;">
                <label class="form-check-label text-secondary ms-1" for="terms">
                    Saya menyetujui <a href="#" class="text-primary text-decoration-none fw-medium">Ketentuan Layanan</a> & <a href="#" class="text-primary text-decoration-none fw-medium">Kebijakan Privasi</a>
                </label>
            </div>
        </div>

        <x-loading-button id="btn-register-submit" text="Daftar Akun" loadingText="Memproses Registrasi..." />
        
        <div class="text-center mt-3" style="font-size: 0.825rem;">
            <span class="text-secondary">Sudah punya akun?</span>
            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold ms-1">Masuk</a>
        </div>
    </x-auth-form>
</x-auth-card>

<script>
    document.getElementById('register-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const pwd = document.getElementById('password').value;
        const confirmPwd = document.getElementById('password_confirmation').value;
        const feedbackArea = document.getElementById('register-feedback-area');
        
        // Form Validation UI: Error password match
        if (pwd !== confirmPwd) {
            feedbackArea.innerHTML = '<div class="alert alert-danger p-3 rounded-3 small d-flex align-items-center gap-2"><i class="bi bi-exclamation-octagon-fill"></i> Konfirmasi kata sandi tidak cocok.</div>';
            feedbackArea.style.display = 'block';
            return;
        }
        
        feedbackArea.style.display = 'none';
        
        const submitBtn = document.getElementById('btn-register-submit');
        const spinner = submitBtn.querySelector('.spinner-loading-icon');
        const label = submitBtn.querySelector('.button-label-text');
        
        submitBtn.disabled = true;
        spinner.style.display = 'inline-block';
        label.textContent = 'Membuat Akun...';
        
        setTimeout(() => {
            feedbackArea.innerHTML = '<div class="alert alert-success p-3 rounded-3 small d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i> Pendaftaran berhasil! Silakan verifikasi email Anda.</div>';
            feedbackArea.style.display = 'block';
            
            setTimeout(() => {
                // Redirect flow: Register -> Verify Email screen
                window.location.href = '/verify-email';
            }, 1200);
        }, 1500);
    });
</script>
</body>
</html>
