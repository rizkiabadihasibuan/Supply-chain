{{-- ═══════════════════════════════════════════════════
     LOGIN FORM COMPONENT – Authentication Flow
     resources/views/components/login-form.blade.php
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

{{-- Login Form --}}
<form id="login-form" action="{{ route('login') }}" method="POST" class="auth-form" novalidate>
    @csrf

    {{-- Email --}}
    <div class="auth-input-group mb-3">
        <label for="login-email" class="form-label">Alamat Email</label>
        <input 
            type="email" 
            class="form-control @error('email') is-invalid @enderror" 
            id="login-email" 
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
        <label for="login-password" class="form-label">Kata Sandi</label>
        <div class="auth-pwd-wrapper">
            <input 
                type="password" 
                class="form-control @error('password') is-invalid @enderror" 
                id="login-password" 
                name="password" 
                placeholder="••••••••" 
                required
                minlength="8"
                autocomplete="current-password"
            >
            <button type="button" class="auth-pwd-toggle" data-target="login-password" aria-label="Toggle password">
                <i class="bi bi-eye"></i>
            </button>
        </div>
        <div class="invalid-feedback">Kata sandi minimal 8 karakter.</div>
    </div>

    {{-- Remember + Forgot --}}
    <div class="d-flex justify-content-between align-items-center mb-3" style="font-size: .84rem;">
        <div class="form-check">
            <input type="checkbox" class="form-check-input" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }} style="width: 17px; height: 17px; border-radius: 4px;">
            <label class="form-check-label ms-1" style="color: var(--auth-muted);" for="remember">Ingat Saya</label>
        </div>
        <a href="{{ route('forgot-password') }}" class="auth-link" style="font-size: .84rem;">Lupa Sandi?</a>
    </div>

    {{-- Submit --}}
    <x-auth-button id="btn-login" type="submit">
        <span class="auth-spinner"></span>
        <span class="auth-btn-label">Masuk</span>
    </x-auth-button>

    {{-- Divider --}}
    <div class="auth-divider">
        <span>atau</span>
    </div>

    {{-- Quick Demo Credentials Helper for Mobile & Hosting --}}
    <div class="p-3 mb-3 border rounded-3 bg-light" style="background-color: #F8FAFC !important; border-color: #E2E8F0 !important;">
        <span class="d-block text-secondary small fw-semibold mb-2"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Login Cepat Demo (1-Klik Mobile):</span>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-primary btn-sm flex-fill py-2" style="font-size: 0.78rem; border-radius: 8px;" onclick="fillCredentials('user@supplychain.com', 'password')">
                <i class="bi bi-person-fill me-1"></i>Demo User
            </button>
            <button type="button" class="btn btn-outline-dark btn-sm flex-fill py-2" style="font-size: 0.78rem; border-radius: 8px;" onclick="fillCredentials('admin@supplychain.com', 'password')">
                <i class="bi bi-shield-lock-fill me-1"></i>Demo Admin
            </button>
        </div>
    </div>

    {{-- Register Link --}}
    <div class="text-center mt-3" style="font-size: .84rem;">
        <span style="color: var(--auth-muted);">Belum punya akun?</span>
        <a href="{{ route('register') }}" class="auth-link ms-1">Daftar Sekarang</a>
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
    function fillCredentials(email, password) {
        document.getElementById('login-email').value = email;
        document.getElementById('login-password').value = password;
        
        // Auto trigger submit after 200ms
        setTimeout(() => {
            document.getElementById('login-form').dispatchEvent(new Event('submit', { cancelable: true }));
            document.getElementById('login-form').submit();
        }, 200);
    }

    // Client-side validation enhancement
    document.getElementById('login-form').addEventListener('submit', function(e) {
        const email = document.getElementById('login-email');
        const password = document.getElementById('login-password');
        let valid = true;

        if (!email.value || !email.validity.valid) {
            email.classList.add('is-invalid');
            valid = false;
        } else {
            email.classList.remove('is-invalid');
        }

        if (!password.value || password.value.length < 8) {
            password.classList.add('is-invalid');
            valid = false;
        } else {
            password.classList.remove('is-invalid');
        }

        if (!valid) {
            e.preventDefault();
            return;
        }

        // Show loading state
        const btn = document.getElementById('btn-login');
        btn.disabled = true;
        const label = btn.querySelector('.auth-btn-label');
        const spinner = btn.querySelector('.auth-spinner');
        if (label) label.textContent = 'Memverifikasi...';
        if (spinner) spinner.style.display = 'inline-block';
    });
</script>
@endpush
