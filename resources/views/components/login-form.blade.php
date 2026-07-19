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

    {{-- Google SSO Placeholder --}}
    <button type="button" class="auth-social-btn" disabled title="SSO Google dinonaktifkan sementara">
        <i class="bi bi-google" style="color: #EA4335;"></i>
        <span>Masuk dengan Google</span>
    </button>

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
