<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Email - SupplyChain Platform</title>
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

<x-auth-card title="Verifikasi Email Anda" subtitle="Kami telah mengirimkan tautan verifikasi ke email Anda. Silakan periksa kotak masuk.">
    <!-- Verification Status Mockups -->
    <div id="verify-feedback-area" class="mb-4">
        <div class="alert alert-info p-3 rounded-3 small d-flex align-items-center gap-2 text-start">
            <i class="bi bi-envelope-fill fs-5 text-primary"></i>
            <div>Email verifikasi telah dikirim ke <strong>user@perusahaan.com</strong></div>
        </div>
    </div>

    <!-- Main Verify Form -->
    <div class="d-flex flex-column gap-3">
        <!-- SVG Envelope Verification Illustration -->
        <div class="my-3 text-center d-flex justify-content-center">
            <svg viewBox="0 0 100 100" style="width: 100px; height: 100px;">
                <circle cx="50" cy="50" r="45" fill="none" stroke="var(--primary)" stroke-width="2" stroke-dasharray="4 4" />
                <rect x="25" y="35" width="50" height="34" rx="4" fill="none" stroke="var(--primary)" stroke-width="2.5" />
                <path d="M25,37 L50,53 L75,37" fill="none" stroke="var(--primary)" stroke-width="2" stroke-linejoin="round" />
            </svg>
        </div>

        <button id="btn-resend-email" class="btn btn-primary w-100 fw-semibold d-flex align-items-center justify-content-center gap-2" style="min-height: 44px; border-radius: 10px;" onclick="resendVerificationEmail()">
            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" style="display: none;" id="resend-spinner"></span>
            <span id="resend-text">Kirim Ulang Email Verifikasi</span>
        </button>

        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 fw-semibold d-flex align-items-center justify-content-center" style="min-height: 44px; border-radius: 10px;">
            Kembali ke Halaman Masuk
        </a>

        <!-- Simulator Simulation triggers success state (Email Verification Success) -->
        <button type="button" class="btn btn-light btn-sm border-0 text-secondary mt-3.5" style="min-height: 38px;" onclick="simulateVerificationSuccess()">
            ⚡ Simulasikan Verifikasi Berhasil (Success)
        </button>
    </div>
</x-auth-card>

<script>
    function resendVerificationEmail() {
        const btn = document.getElementById('btn-resend-email');
        const spinner = document.getElementById('resend-spinner');
        const text = document.getElementById('resend-text');
        
        btn.disabled = true;
        spinner.style.display = 'inline-block';
        text.textContent = 'Mengirim Ulang...';
        
        setTimeout(() => {
            spinner.style.display = 'none';
            text.textContent = 'Kirim Ulang Email Verifikasi';
            btn.disabled = false;
            
            const feedback = document.getElementById('verify-feedback-area');
            feedback.innerHTML = '<div class="alert alert-success p-3 rounded-3 small d-flex align-items-center gap-2 text-start"><i class="bi bi-check-circle-fill fs-5 text-success"></i> Tautan baru berhasil dikirim ulang!</div>';
        }, 1200);
    }

    // Simulate Success flow
    function simulateVerificationSuccess() {
        const feedback = document.getElementById('verify-feedback-area');
        feedback.innerHTML = '<div class="alert alert-success p-3 rounded-3 small d-flex align-items-center gap-2 text-start"><i class="bi bi-patch-check-fill fs-4 text-success"></i> Email Berhasil Diverifikasi! Mengalihkan ke login...</div>';
        
        // Hide illustration and resend button
        document.getElementById('btn-resend-email').style.display = 'none';
        
        setTimeout(() => {
            window.location.href = '/login';
        }, 1500);
    }
</script>
</body>
</html>
