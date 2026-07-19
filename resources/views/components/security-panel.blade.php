{{-- ═══════════════════════════════════════════════════
     SECURITY PANEL COMPONENT – Milestone 3.14
     resources/views/components/security-panel.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card p-4 border-0">
    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-shield-lock-fill text-primary me-2"></i>Keamanan Kata Sandi</h5>

    {{-- Change Password Section --}}
    <form id="form-security" class="needs-validation" novalidate>
        <div class="mb-3.5">
            <label for="current_password" class="form-label small fw-semibold text-secondary mb-1.5">Kata Sandi Saat Ini</label>
            <input type="password" id="current_password" class="form-control" placeholder="••••••••" style="min-height: 44px;" required>
            <div class="invalid-feedback">Masukkan kata sandi saat ini.</div>
        </div>

        <div class="mb-3.5">
            <label for="new_password" class="form-label small fw-semibold text-secondary mb-1.5">Kata Sandi Baru</label>
            <input type="password" id="new_password" class="form-control" placeholder="••••••••" style="min-height: 44px;" required minlength="8">
            <div class="invalid-feedback">Kata sandi baru minimal 8 karakter.</div>
        </div>

        <div class="mb-3.5">
            <label for="confirm_password" class="form-label small fw-semibold text-secondary mb-1.5">Konfirmasi Kata Sandi Baru</label>
            <input type="password" id="confirm_password" class="form-control" placeholder="••••••••" style="min-height: 44px;" required>
            <div class="invalid-feedback">Konfirmasikan kata sandi baru Anda.</div>
        </div>

        <div class="mt-3">
            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Perbarui Sandi</button>
        </div>
    </form>

    <hr class="text-secondary opacity-25 my-4">

    {{-- Two Factor Authentication Section --}}
    <div class="mb-4">
        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-shield-check text-primary me-1.5"></i>Otentikasi Dua Faktor (2FA)</h6>
        <p class="text-secondary small mb-3">Tingkatkan keamanan akun Anda dengan menambahkan lapisan verifikasi tambahan saat masuk.</p>
        
        <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #FAFCFF !important;">
            <div>
                <span class="fw-semibold text-dark d-block" style="font-size: 0.875rem;">Aplikasi Authenticator (Rekomendasi)</span>
                <span class="text-secondary small">Gunakan aplikasi seperti Google Authenticator atau Microsoft Authenticator.</span>
            </div>
            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch" id="two_factor_toggle" style="width: 44px; height: 22px; cursor: pointer;">
            </div>
        </div>
    </div>

    <hr class="text-secondary opacity-25 my-4">

    {{-- Active Sessions Section --}}
    <div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold text-dark mb-0"><i class="bi bi-pc-display-horizontal text-primary me-1.5"></i>Sesi Perangkat Aktif</h6>
            <button class="btn btn-sm btn-outline-danger" style="min-height: 34px;" onclick="ProfileSecurity.terminateAllSessions()">Keluarkan Semua Sesi Lain</button>
        </div>
        
        <div class="d-flex flex-column gap-3">
            {{-- Current Session --}}
            <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3" style="background-color: #FAFCFF !important;">
                <div class="fs-2 text-primary"><i class="bi bi-laptop"></i></div>
                <div>
                    <span class="fw-bold text-dark d-block" style="font-size: 0.875rem;">Windows PC - Chrome (Sesi Saat Ini)</span>
                    <span class="text-secondary small d-block">Jakarta, Indonesia | IP: 192.168.1.45 | Login: Baru Saja</span>
                </div>
                <span class="badge bg-success ms-auto">Aktif</span>
            </div>

            {{-- Other Sessions --}}
            <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3 other-device-session" style="background-color: #FAFCFF !important;">
                <div class="fs-2 text-secondary"><i class="bi bi-phone"></i></div>
                <div>
                    <span class="fw-bold text-dark d-block" style="font-size: 0.875rem;">iPhone 14 - Safari (Mobile)</span>
                    <span class="text-secondary small d-block">Jakarta, Indonesia | IP: 192.168.1.11 | Login: 1 Jam Lalu</span>
                </div>
                <button class="btn btn-sm btn-light border ms-auto" style="min-height: 34px;" onclick="ProfileSecurity.terminateSession('iphone', this)">Keluarkan</button>
            </div>

            <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3 other-device-session" style="background-color: #FAFCFF !important;">
                <div class="fs-2 text-secondary"><i class="bi bi-laptop"></i></div>
                <div>
                    <span class="fw-bold text-dark d-block" style="font-size: 0.875rem;">MacBook Pro - Firefox (MacOS)</span>
                    <span class="text-secondary small d-block">Bandung, Indonesia | IP: 182.23.4.15 | Login: 2 Hari Lalu</span>
                </div>
                <button class="btn btn-sm btn-light border ms-auto" style="min-height: 34px;" onclick="ProfileSecurity.terminateSession('macbook', this)">Keluarkan</button>
            </div>
        </div>
    </div>
</div>
