{{-- ═══════════════════════════════════════════════════
     FORGOT PASSWORD FORM COMPONENT – Authentication Flow
     resources/views/components/forgot-form.blade.php
     ═══════════════════════════════════════════════════ --}}

{{-- Flash Message Display --}}
@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert" style="font-size:.88rem;border-radius:10px;">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" role="alert" style="font-size:.88rem;border-radius:10px;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

{{-- Feedback Area (client-side) --}}
<div id="forgot-feedback" style="display: none;"></div>

{{-- Forgot Form (UI Only – email belum diimplementasi) --}}
<form id="forgot-form" action="#" method="POST" class="auth-form" novalidate>
    @csrf

    {{-- Info Alert --}}
    <div class="alert alert-info d-flex align-items-start gap-2 mb-3" role="alert" style="font-size:.84rem;border-radius:10px;background:rgba(6,182,212,.08);border-color:rgba(6,182,212,.2);color:#0E7490;">
        <i class="bi bi-info-circle-fill mt-1"></i>
        <div>Fitur reset password via email belum diaktifkan. Hubungi administrator untuk reset password.</div>
    </div>

    {{-- Email --}}
    <div class="auth-input-group mb-3">
        <label for="forgot-email" class="form-label">Alamat Email</label>
        <input 
            type="email" 
            class="form-control" 
            id="forgot-email" 
            name="email" 
            placeholder="nama@perusahaan.com" 
            required
            autocomplete="email"
        >
        <div class="invalid-feedback">Masukkan alamat email yang valid.</div>
    </div>

    {{-- Submit --}}
    <x-auth-button id="btn-forgot" type="submit">
        <span class="auth-spinner"></span>
        <span class="auth-btn-label">Kirim Link Reset Password</span>
    </x-auth-button>

    {{-- Back to login --}}
    <div class="text-center mt-3">
        <a href="{{ route('login') }}" class="auth-link d-inline-flex align-items-center gap-1" style="font-size: .84rem;">
            <i class="bi bi-arrow-left"></i> Kembali ke Halaman Masuk
        </a>
    </div>
</form>

@push('scripts')
<script>
    document.getElementById('forgot-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const email = document.getElementById('forgot-email');
        if (!email.value || !email.validity.valid) {
            email.classList.add('is-invalid');
            return;
        }
        email.classList.remove('is-invalid');

        // Show loading
        const btn = document.getElementById('btn-forgot');
        btn.disabled = true;
        const label = btn.querySelector('.auth-btn-label');
        const spinner = btn.querySelector('.auth-spinner');
        if (label) label.textContent = 'Mengirim Tautan...';
        if (spinner) spinner.style.display = 'inline-block';

        // Simulate (UI only – email not implemented)
        setTimeout(() => {
            const feedback = document.getElementById('forgot-feedback');
            feedback.style.display = 'block';
            feedback.className = 'alert alert-warning d-flex align-items-center gap-2 mb-3';
            feedback.style.fontSize = '.88rem';
            feedback.style.borderRadius = '10px';
            feedback.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i><div>Fitur ini belum aktif. Silakan hubungi administrator untuk reset password.</div>';
            
            btn.disabled = false;
            if (label) label.textContent = 'Kirim Link Reset Password';
            if (spinner) spinner.style.display = 'none';
        }, 1500);
    });
</script>
@endpush
