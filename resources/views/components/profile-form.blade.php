{{-- ═══════════════════════════════════════════════════
     PROFILE FORM COMPONENT – Milestone 3.14
     resources/views/components/profile-form.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'fullname' => 'Administrator Utama',
    'email' => 'admin@supplychain.com',
    'phone' => '+62 812-3456-7890',
    'company' => 'Mitra Logistik Global PT',
    'designation' => 'Kepala Operasional Command Center',
    'country' => 'ID',
    'timezone' => 'Asia/Jakarta',
    'language' => 'id'
])

<div class="card p-4 border-0">
    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-person-vcard text-primary me-2"></i>Ubah Informasi Profil</h5>

    <form id="form-edit-profile" class="needs-validation" novalidate>
        <div class="row g-3">
            {{-- Full Name --}}
            <div class="col-sm-6 profile-input-group">
                <label for="fullname" class="form-label small fw-semibold text-secondary mb-1.5">Nama Lengkap</label>
                <input type="text" id="fullname" class="form-control" value="{{ $fullname }}" style="min-height: 44px;" required minlength="3">
                <div class="invalid-feedback">Nama lengkap minimal 3 karakter.</div>
            </div>

            {{-- Email --}}
            <div class="col-sm-6 profile-input-group">
                <label for="email" class="form-label small fw-semibold text-secondary mb-1.5">Alamat Email</label>
                <input type="email" id="email" class="form-control" value="{{ $email }}" style="min-height: 44px;" required>
                <div class="invalid-feedback">Masukkan alamat email yang valid.</div>
            </div>

            {{-- Phone Number --}}
            <div class="col-sm-6 profile-input-group">
                <label for="phone" class="form-label small fw-semibold text-secondary mb-1.5">Nomor Telepon</label>
                <input type="text" id="phone" class="form-control" value="{{ $phone }}" style="min-height: 44px;">
            </div>

            {{-- Company --}}
            <div class="col-sm-6 profile-input-group">
                <label for="company" class="form-label small fw-semibold text-secondary mb-1.5">Nama Perusahaan</label>
                <input type="text" id="company" class="form-control" value="{{ $company }}" style="min-height: 44px;">
            </div>

            {{-- Job Position --}}
            <div class="col-sm-6 profile-input-group">
                <label for="designation" class="form-label small fw-semibold text-secondary mb-1.5">Jabatan Pekerjaan</label>
                <input type="text" id="designation" class="form-control" value="{{ $designation }}" style="min-height: 44px;">
            </div>

            {{-- Country --}}
            <div class="col-sm-6 profile-input-group">
                <label for="country" class="form-label small fw-semibold text-secondary mb-1.5">Negara Domisili</label>
                <select id="country" class="form-select" style="min-height: 44px;">
                    <option value="ID" {{ $country === 'ID' ? 'selected' : '' }}>🇮🇩 Indonesia</option>
                    <option value="SG" {{ $country === 'SG' ? 'selected' : '' }}>🇸🇬 Singapura</option>
                    <option value="US" {{ $country === 'US' ? 'selected' : '' }}>🇺🇸 Amerika Serikat</option>
                    <option value="CN" {{ $country === 'CN' ? 'selected' : '' }}>🇨🇳 China</option>
                </select>
            </div>

            {{-- Timezone --}}
            <div class="col-sm-6 profile-input-group">
                <label for="timezone" class="form-label small fw-semibold text-secondary mb-1.5">Zona Waktu (Timezone)</label>
                <select id="timezone" class="form-select" style="min-height: 44px;">
                    <option value="Asia/Jakarta" {{ $timezone === 'Asia/Jakarta' ? 'selected' : '' }}>Asia/Jakarta (WIB) - GMT+07:00</option>
                    <option value="Asia/Singapore" {{ $timezone === 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore (SGT) - GMT+08:00</option>
                    <option value="UTC" {{ $timezone === 'UTC' ? 'selected' : '' }}>Coordinated Universal Time (UTC) - GMT+00:00</option>
                </select>
            </div>

            {{-- Language --}}
            <div class="col-sm-6 profile-input-group">
                <label for="language" class="form-label small fw-semibold text-secondary mb-1.5">Bahasa Antarmuka</label>
                <select id="language" class="form-select" style="min-height: 44px;">
                    <option value="id" {{ $language === 'id' ? 'selected' : '' }}>Bahasa Indonesia</option>
                    <option value="en" {{ $language === 'en' ? 'selected' : '' }}>English (US)</option>
                </select>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Simpan Perubahan</button>
            <button type="reset" class="btn btn-light px-4" style="min-height: 44px;">Reset</button>
        </div>
    </form>
</div>
