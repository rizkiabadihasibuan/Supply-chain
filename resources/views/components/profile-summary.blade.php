{{-- ═══════════════════════════════════════════════════
     PROFILE SUMMARY COMPONENT – Milestone 3.14
     resources/views/components/profile-summary.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop',
    'fullname' => 'Administrator Utama',
    'email' => 'admin@supplychain.com',
    'role' => 'Administrator',
    'status' => 'Aktif',
    'joinedDate' => '17 Juli 2026',
    'lastLogin' => 'Sabtu, 18 Juli 2026 - 09:47 WIB'
])

<div class="profile-card-summary text-center">
    <div class="row align-items-center g-4 text-md-start">
        {{-- Avatar Section --}}
        <div class="col-12 col-md-auto text-center">
            <div class="profile-avatar-wrapper">
                <img src="{{ $avatar }}" alt="User Avatar" class="profile-avatar-img" id="summary-avatar-img">
                <div class="profile-avatar-upload-btn" data-bs-toggle="modal" data-bs-target="#avatarUploadModal" title="Unggah Foto Baru">
                    <i class="bi bi-camera-fill" style="font-size: 0.85rem;"></i>
                </div>
            </div>
        </div>

        {{-- Details Section --}}
        <div class="col-12 col-md-7 col-lg-8">
            <div class="d-flex align-items-center justify-content-center justify-content-md-start gap-2 flex-wrap mb-1.5">
                <h4 class="profile-details-title mb-0" id="summary-fullname-val">{{ $fullname }}</h4>
                <span class="badge bg-primary">{{ $role }}</span>
                <span class="badge badge-success">{{ $status }}</span>
            </div>
            <p class="profile-details-subtitle mb-3" id="summary-email-val">{{ $email }}</p>
            
            <div class="profile-meta-grid">
                <div class="profile-meta-item">
                    <span class="profile-meta-label">Tanggal Bergabung</span>
                    <span class="profile-meta-value">{{ $joinedDate }}</span>
                </div>
                <div class="profile-meta-item">
                    <span class="profile-meta-label">Login Terakhir</span>
                    <span class="profile-meta-value">{{ $lastLogin }}</span>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="col-12 col-lg-2 ms-lg-auto d-flex flex-column gap-2 text-center text-md-start">
            <button class="btn btn-primary btn-sm w-100" onclick="ProfileTabs.switchTab('edit-profile', document.getElementById('tab-edit-profile'))">
                <i class="bi bi-pencil-square me-1.5"></i>Edit Profil
            </button>
            <button class="btn btn-outline-primary btn-sm w-100" onclick="ProfileTabs.switchTab('security', document.getElementById('tab-security'))">
                <i class="bi bi-shield-lock me-1.5"></i>Ganti Sandi
            </button>
            <button class="btn btn-light btn-sm w-100" data-bs-toggle="modal" data-bs-target="#avatarUploadModal">
                <i class="bi bi-cloud-arrow-up me-1.5"></i>Upload Avatar
            </button>
        </div>
    </div>
</div>
