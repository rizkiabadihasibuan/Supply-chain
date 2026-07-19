{{-- ═══════════════════════════════════════════════════
     REGISTER FORM COMPONENT – Authentication Flow
     resources/views/components/register-form.blade.php
     ═══════════════════════════════════════════════════ --}}

{{-- Server-side Error Display --}}
@if(session('error'))
    <div class="alert alert-danger d-flex align-items-center gap-2 mb-3" role="alert" style="font-size:.88rem;border-radius:10px;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>{{ session('error') }}</div>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success d-flex align-items-center gap-2 mb-3" role="alert" style="font-size:.88rem;border-radius:10px;">
        <i class="bi bi-check-circle-fill"></i>
        <div>{{ session('success') }}</div>
    </div>
@endif

{{-- Validation Errors --}}
@if($errors->any())
    <div class="alert alert-danger mb-3" role="alert" style="font-size:.88rem;border-radius:10px;">
        <div class="d-flex align-items-center gap-2 mb-1">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <strong>Terjadi kesalahan:</strong>
        </div>
        <ul class="mb-0 ps-3">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Register Form --}}
<form id="register-form" action="{{ route('register') }}" method="POST" class="auth-form" novalidate>
    @csrf

    {{-- Nama Lengkap --}}
    <div class="auth-input-group mb-3">
        <label for="reg-fullname" class="form-label">Nama Lengkap</label>
        <input 
            type="text" 
            class="form-control @error('name') is-invalid @enderror" 
            id="reg-fullname" 
            name="name" 
            value="{{ old('name') }}"
            placeholder="John Doe" 
            required
            minlength="3"
            autocomplete="name"
        >
        <div class="invalid-feedback">Nama lengkap minimal 3 karakter.</div>
    </div>

    {{-- Email --}}
    <div class="auth-input-group mb-3">
        <label for="reg-email" class="form-label">Alamat Email</label>
        <input 
            type="email" 
            class="form-control @error('email') is-invalid @enderror" 
            id="reg-email" 
            name="email" 
            value="{{ old('email') }}"
            placeholder="nama@perusahaan.com" 
            required
            autocomplete="email"
        >
        <div class="invalid-feedback">Masukkan alamat email yang valid.</div>
    </div>

    {{-- Password --}}
    <div class="auth-input-group mb-3">
        <label for="reg-password" class="form-label">Kata Sandi</label>
        <div class="auth-pwd-wrapper">
            <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="reg-password" 
                name="password" 
                placeholder="••••••••" 
                required
                minlength="8"
                autocomplete="new-password"
            >
            <button type="button" class="auth-pwd-toggle" data-target="reg-password" aria-label="Toggle password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="invalid-feedback">Kata sandi minimal 8 karakter.</div>
        
        {{-- Password strength meter --}}
        <div class="auth-pwd-strength mt-2" id="pwd-strength-meter" style="display: none;">
            <div class="d-flex gap-1">
                <div class="auth-pwd-bar"></div>
                <div class="auth-pwd-bar"></div>
                <div class="auth-pwd-bar"></div>
                <div class="auth-pwd-bar"></div>
            </div>
            <small class="auth-pwd-strength-text mt-1 d-block" style="font-size: .75rem; color: var(--auth-muted);"></small>
        </div>
    </div>

    {{-- Konfirmasi Password --}}
    <div class="auth-input-group mb-3">
        <label for="reg-password-confirm" class="form-label">Konfirmasi Kata Sandi</label>
        <div class="auth-pwd-wrapper">
            <input 
                type="password" 
                class="form-control" 
                id="reg-password-confirm" 
                name="password_confirmation" 
                placeholder="••••••••" 
                required
                minlength="8"
                autocomplete="new-password"
            >
            <button type="button" class="auth-pwd-toggle" data-target="reg-password-confirm" aria-label="Toggle password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="invalid-feedback">Konfirmasi kata sandi tidak cocok.</div>
    </div>

    {{-- Terms Checkbox --}}
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="reg-terms" name="terms" required style="width: 17px; height: 17px; border-radius: 4px;">
        <label class="form-check-label ms-1" style="font-size: .84rem; color: var(--auth-muted);" for="reg-terms">
            Saya menyetujui <a href="#" class="auth-link">Ketentuan Layanan</a> & <a href="#" class="auth-link">Kebijakan Privasi</a>
        </label>
        <div class="invalid-feedback">Anda harus menyetujui ketentuan layanan.</div>
    </div>

    {{-- Submit --}}
    <x-auth-button id="btn-register" type="submit">
        <span class="auth-spinner"></span>
        <span class="auth-btn-label">Daftar Akun</span>
    </x-auth-button>

    {{-- Login Link --}}
    <div class="text-center mt-3" style="font-size: .84rem;">
        <span style="color: var(--auth-muted);">Sudah punya akun?</span>
        <a href="{{ route('login') }}" class="auth-link ms-1">Masuk</a>
    </div>

    {{-- Back to Landing --}}
    <div class="text-center mt-2">
        <a href="{{ route('landing') }}" class="auth-link d-inline-flex align-items-center gap-1" style="font-size: .84rem;">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</form>

@push('scripts')
<script>
    // Password confirmation match
    document.getElementById('reg-password-confirm').addEventListener('input', function() {
        const pwd = document.getElementById('reg-password').value;
        if (this.value && this.value !== pwd) {
            this.classList.add('is-invalid');
            this.classList.remove('is-valid');
        } else if (this.value && this.value === pwd) {
            this.classList.remove('is-invalid');
            this.classList.add('is-valid');
        }
    });

    // Password strength meter
    document.getElementById('reg-password').addEventListener('input', function() {
        if (typeof AuthValidation !== 'undefined') {
            AuthValidation.checkPasswordStrength(this.value);
        }
    });

    // Form submission with loading state
    document.getElementById('register-form').addEventListener('submit', function(e) {
        const pwd = document.getElementById('reg-password').value;
        const confirmPwd = document.getElementById('reg-password-confirm').value;

        // Check password match
        if (pwd !== confirmPwd) {
            e.preventDefault();
            document.getElementById('reg-password-confirm').classList.add('is-invalid');
            return;
        }

        // Check terms
        if (!document.getElementById('reg-terms').checked) {
            e.preventDefault();
            document.getElementById('reg-terms').classList.add('is-invalid');
            return;
        }

        // Show loading state
        const btn = document.getElementById('btn-register');
        btn.disabled = true;
        const label = btn.querySelector('.auth-btn-label');
        const spinner = btn.querySelector('.auth-spinner');
        if (label) label.textContent = 'Membuat Akun...';
        if (spinner) spinner.style.display = 'inline-block';
    });
</script>
@endpush
