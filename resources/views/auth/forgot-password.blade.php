<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Sandi - SupplyChain Platform</title>
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

<x-auth-card title="Lupa Kata Sandi" subtitle="Masukkan email terdaftar Anda untuk menerima tautan atur ulang sandi.">
    <div id="forgot-feedback-area" class="mb-3" style="display: none;">
        <x-validation-message type="success" message="Tautan pemulihan sandi telah dikirim ke alamat email Anda!" />
    </div>

    <x-auth-form id="forgot-form" action="#">
        <x-auth-input label="Alamat Email" type="email" id="email" name="email" placeholder="nama@perusahaan.com" required="true" />

        <x-loading-button id="btn-forgot-submit" text="Kirim Link Atur Ulang" loadingText="Mengirim Tautan..." />
        
        <div class="text-center mt-3" style="font-size: 0.825rem;">
            <a href="{{ route('login') }}" class="text-primary text-decoration-none fw-semibold"><i class="bi bi-arrow-left me-1"></i>Kembali Masuk</a>
        </div>
    </x-auth-form>
</x-auth-card>

<script>
    document.getElementById('forgot-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = document.getElementById('btn-forgot-submit');
        const spinner = submitBtn.querySelector('.spinner-loading-icon');
        const label = submitBtn.querySelector('.button-label-text');
        
        submitBtn.disabled = true;
        spinner.style.display = 'inline-block';
        label.textContent = 'Mengirim Tautan...';
        
        setTimeout(() => {
            document.getElementById('forgot-feedback-area').style.display = 'block';
            
            setTimeout(() => {
                // Redirect flow: Forgot -> Reset password page mockup
                window.location.href = '/reset-password';
            }, 1500);
        }, 1500);
    });
</script>
</body>
</html>
