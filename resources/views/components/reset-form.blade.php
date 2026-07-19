{{-- ═══════════════════════════════════════════════════
     RESET PASSWORD FORM COMPONENT – Milestone 3.13
     resources/views/components/reset-form.blade.php
     ═══════════════════════════════════════════════════ --}}

{{-- Feedback Area --}}
<div id="reset-feedback" style="display: none;"></div>

{{-- Reset Form --}}
<form id="reset-form" action="#" method="POST" class="auth-form" novalidate>
    @csrf

    {{-- Email (readonly mockup) --}}
    <div class="auth-input-group mb-3">
        <label for="reset-email" class="form-label">Alamat Email</label>
        <input 
            type="email" 
            class="form-control" 
            id="reset-email" 
            name="email" 
            value="user@perusahaan.com" 
            readonly
            style="background: #F1F5F9; color: var(--auth-muted);"
        >
    </div>

    {{-- Password Baru --}}
    <div class="auth-input-group mb-3">
        <label for="reset-password" class="form-label">Kata Sandi Baru</label>
        <div class="auth-pwd-wrapper">
            <input 
                type="password" 
                class="form-control" 
                id="reset-password" 
                name="password" 
                placeholder="••••••••" 
                required
                minlength="8"
                autocomplete="new-password"
            >
            <button type="button" class="auth-pwd-toggle" data-target="reset-password" aria-label="Toggle password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="invalid-feedback">Kata sandi baru minimal 8 karakter.</div>
    </div>

    {{-- Konfirmasi Password --}}
    <div class="auth-input-group mb-3">
        <label for="reset-password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
        <div class="auth-pwd-wrapper">
            <input 
                type="password" 
                class="form-control" 
                id="reset-password-confirm" 
                name="password_confirmation" 
                placeholder="••••••••" 
                required
                minlength="8"
                autocomplete="new-password"
            >
            <button type="button" class="auth-pwd-toggle" data-target="reset-password-confirm" aria-label="Toggle password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="invalid-feedback">Konfirmasi kata sandi tidak cocok.</div>
    </div>

    {{-- Submit --}}
    <x-auth-button id="btn-reset" type="submit">
        <span class="auth-spinner"></span>
        <span class="auth-btn-label">Reset Password</span>
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
    // Password confirmation check
    document.getElementById('reset-password-confirm').addEventListener('input', function() {
        const pwd = document.getElementById('reset-password').value;
        if (this.value && this.value !== pwd) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value && this.value === pwd) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    document.getElementById('reset-form').addEventListener('submit', function(e) {
        e.preventDefault();

        const pwd = document.getElementById('reset-password').value;
        const confirmPwd = document.getElementById('reset-password-confirm').value;

        if (pwd !== confirmPwd) {
            Auth.showFeedback('reset-feedback', 'danger', 'Konfirmasi kata sandi tidak cocok.');
            document.getElementById('reset-password-confirm').classList.add('is-invalid');
            return;
        }

        if (!AuthValidation.validateForm(this)) return;

        Auth.startLoading('btn-reset', 'Menyimpan...');

        setTimeout(() => {
            Auth.showFeedback('reset-feedback', 'success', 'Kata sandi berhasil direset! Mengalihkan ke halaman login...');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
        }, 1500);
    });
</script>
@endpush
