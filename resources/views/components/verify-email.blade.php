{{-- ═══════════════════════════════════════════════════
     EMAIL VERIFICATION COMPONENT – Milestone 3.13
     resources/views/components/verify-email.blade.php
     ═══════════════════════════════════════════════════ --}}

{{-- Feedback Area --}}
<div id="verify-feedback">
    <div class="auth-alert auth-alert-info">
        <i class="bi bi-envelope-fill"></i>
        <span>Email verifikasi telah dikirim ke <strong>user@perusahaan.com</strong></span>
    </div>
</div>

{{-- Verification Content --}}
<div class="d-flex flex-column gap-3">

    {{-- SVG Envelope illustration --}}
    <div class="text-center my-2">
        <svg viewBox="0 0 120 100" style="width: 90px; height: auto;">
            <circle cx="60" cy="50" r="44" fill="none" stroke="#2563EB" stroke-width="1.5" stroke-dasharray="4 4" opacity=".25"/>
            <rect x="28" y="32" width="64" height="40" rx="6" fill="none" stroke="#2563EB" stroke-width="2.5" opacity=".7"/>
            <path d="M28,34 L60,56 L92,34" fill="none" stroke="#2563EB" stroke-width="2" stroke-linejoin="round" opacity=".6"/>
            <circle cx="60" cy="50" r="44" fill="none" stroke="#2563EB" stroke-width="1" opacity=".15">
                <animate attributeName="r" from="44" to="52" dur="2s" repeatCount="indefinite"/>
                <animate attributeName="opacity" from="0.2" to="0" dur="2s" repeatCount="indefinite"/>
            </circle>
        </svg>
    </div>

    {{-- Resend Button --}}
    <x-auth-button id="btn-resend" type="button" onclick="resendVerification()">
        <span class="auth-spinner" id="resend-spinner"></span>
        <span class="auth-btn-label" id="resend-label">Kirim Ulang Email</span>
    </x-auth-button>

    {{-- Logout / Back to Login --}}
    <a href="{{ route('login') }}" class="auth-btn" style="background: transparent; border: 1px solid var(--auth-border); color: var(--auth-text); text-decoration: none;">
        Keluar
    </a>

    {{-- Simulation trigger --}}
    <button type="button" 
            class="btn btn-sm border-0 mt-2" 
            style="background: #F1F5F9; color: var(--auth-muted); font-size: .8rem; border-radius: 8px; height: 38px;"
            onclick="simulateVerificationSuccess()">
        ⚡ Simulasikan Verifikasi Berhasil
    </button>
</div>

@push('scripts')
<script>
    function resendVerification() {
        Auth.startLoading('btn-resend', 'Mengirim Ulang...');

        setTimeout(() => {
            Auth.stopLoading('btn-resend', 'Kirim Ulang Email');
            Auth.showFeedback('verify-feedback', 'success', 'Tautan baru berhasil dikirim ulang!');
        }, 1200);
    }

    function simulateVerificationSuccess() {
        Auth.showFeedback('verify-feedback', 'success', 'Email Berhasil Diverifikasi! Mengalihkan ke login...');
        document.getElementById('btn-resend').style.display = 'none';

        setTimeout(() => {
            window.location.href = '/login';
        }, 1500);
    }
</script>
@endpush
