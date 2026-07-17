<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Sandi - SupplyChain Platform</title>
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

<x-auth-card title="Atur Ulang Sandi" subtitle="Buat kata sandi baru yang aman untuk akun Anda.">
    <div id="reset-feedback-area" class="mb-3" style="display: none;">
        <!-- Injected validation messages -->
    </div>

    <x-auth-form id="reset-form" action="#">
        <x-auth-input label="Kata Sandi Baru" type="password" id="password" name="password" placeholder="••••••••" required="true">
            <x-password-toggle inputId="password" />
        </x-auth-input>

        <x-auth-input label="Konfirmasi Kata Sandi Baru" type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required="true">
            <x-password-toggle inputId="password_confirmation" />
        </x-auth-input>

        <x-loading-button id="btn-reset-submit" text="Simpan Kata Sandi" loadingText="Menyimpan Sandi Baru..." />
    </x-auth-form>
</x-auth-card>

<script>
    document.getElementById('reset-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const pwd = document.getElementById('password').value;
        const confirmPwd = document.getElementById('password_confirmation').value;
        const feedbackArea = document.getElementById('reset-feedback-area');
        
        if (pwd !== confirmPwd) {
            feedbackArea.innerHTML = '<div class="alert alert-danger p-3 rounded-3 small d-flex align-items-center gap-2"><i class="bi bi-exclamation-octagon-fill"></i> Konfirmasi kata sandi tidak cocok.</div>';
            feedbackArea.style.display = 'block';
            return;
        }
        
        feedbackArea.style.display = 'none';
        
        const submitBtn = document.getElementById('btn-reset-submit');
        const spinner = submitBtn.querySelector('.spinner-loading-icon');
        const label = submitBtn.querySelector('.button-label-text');
        
        submitBtn.disabled = true;
        spinner.style.display = 'inline-block';
        label.textContent = 'Menyimpan Sandi Baru...';
        
        setTimeout(() => {
            feedbackArea.innerHTML = '<div class="alert alert-success p-3 rounded-3 small d-flex align-items-center gap-2"><i class="bi bi-check-circle-fill"></i> Kata sandi berhasil diperbarui! Mengalihkan ke login...</div>';
            feedbackArea.style.display = 'block';
            
            setTimeout(() => {
                // Redirect flow: Reset -> Login
                window.location.href = '/login';
            }, 1500);
        }, 1500);
    });
</script>
</body>
</html>
