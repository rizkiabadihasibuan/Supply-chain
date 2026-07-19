{{-- ═══════════════════════════════════════════════════
     PREFERENCES PANEL COMPONENT – Milestone 3.14
     resources/views/components/preferences-panel.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card p-4 border-0">
    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-sliders text-primary me-2"></i>Preferensi Aplikasi</h5>

    {{-- Theme Mode Selector --}}
    <div class="mb-4">
        <label class="form-label small fw-semibold text-secondary mb-2">Tema Visual Dashboard</label>
        <div class="theme-selector-grid">
            <div class="theme-card active" onclick="ProfilePreferences.switchTheme('light', this)">
                <div class="theme-preview-box theme-preview-light"></div>
                <span class="theme-title">Light Mode</span>
            </div>
            <div class="theme-card" onclick="ProfilePreferences.switchTheme('dark', this)">
                <div class="theme-preview-box theme-preview-dark"></div>
                <span class="theme-title">Dark Mode</span>
            </div>
            <div class="theme-card" onclick="ProfilePreferences.switchTheme('system', this)">
                <div class="theme-preview-box theme-preview-system"></div>
                <span class="theme-title">System Default</span>
            </div>
        </div>
    </div>

    <hr class="text-secondary opacity-25 my-4">

    {{-- Localization Options --}}
    <form id="form-regional">
        <div class="row g-3">
            {{-- Interface Language --}}
            <div class="col-sm-6 profile-input-group">
                <label for="select_language" class="form-label small fw-semibold text-secondary mb-1.5">Bahasa Antarmuka (Language)</label>
                <select id="select_language" class="form-select" style="min-height: 44px;">
                    <option value="id" selected>Bahasa Indonesia</option>
                    <option value="en">English (US)</option>
                </select>
            </div>

            {{-- Timezone --}}
            <div class="col-sm-6 profile-input-group">
                <label for="select_timezone" class="form-label small fw-semibold text-secondary mb-1.5">Zona Waktu (Timezone)</label>
                <select id="select_timezone" class="form-select" style="min-height: 44px;">
                    <option value="wib" selected>Asia/Jakarta (WIB) - GMT+07:00</option>
                    <option value="sgt">Asia/Singapore (SGT) - GMT+08:00</option>
                    <option value="utc">Coordinated Universal Time (UTC) - GMT+00:00</option>
                </select>
            </div>

            {{-- Date Format --}}
            <div class="col-sm-6 profile-input-group">
                <label for="select_date_format" class="form-label small fw-semibold text-secondary mb-1.5">Format Tanggal</label>
                <select id="select_date_format" class="form-select" style="min-height: 44px;">
                    <option value="d-m-Y" selected>DD-MM-YYYY (Contoh: 18-07-2026)</option>
                    <option value="Y-m-d">YYYY-MM-DD (Contoh: 2026-07-18)</option>
                </select>
            </div>

            {{-- Currency Format --}}
            <div class="col-sm-6 profile-input-group">
                <label for="select_currency" class="form-label small fw-semibold text-secondary mb-1.5">Mata Uang Acuan (Base Currency)</label>
                <select id="select_currency" class="form-select" style="min-height: 44px;">
                    <option value="idr" selected>IDR (Rp) - Rupiah Indonesia</option>
                    <option value="usd">USD ($) - Dollar Amerika Serikat</option>
                    <option value="eur">EUR (€) - Euro Eropa</option>
                </select>
            </div>

            {{-- Dashboard Layout --}}
            <div class="col-12 profile-input-group">
                <label for="select_layout" class="form-label small fw-semibold text-secondary mb-1.5">Tata Letak Utama (Dashboard Layout)</label>
                <select id="select_layout" class="form-select" style="min-height: 44px;">
                    <option value="compact" selected>Compact Layout (Default - Optimal Monitor 14")</option>
                    <option value="fluid">Fluid Grid (Layar Lebar / Multimonitor)</option>
                </select>
            </div>
        </div>

        <div class="mt-4 pt-2">
            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Simpan Preferensi</button>
        </div>
    </form>
</div>
