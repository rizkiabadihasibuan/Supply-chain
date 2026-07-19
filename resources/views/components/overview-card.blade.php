{{-- ═══════════════════════════════════════════════════
     OVERVIEW CARD COMPONENT – Milestone 3.14
     resources/views/components/overview-card.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'profileCompletion' => 85,
    'totalFavorites' => 8,
    'lastActivity' => 'Membandingkan Risiko Rantai Pasok ID vs US',
    'lastLogin' => 'Baru Saja',
    'accountStatus' => 'Aktif'
])

<div class="card p-4 border-0">
    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-person-fill text-primary me-2"></i>Ringkasan Akun Pengguna</h5>

    <div class="row g-4">
        {{-- Profile Completion --}}
        <div class="col-12 col-md-6">
            <div class="p-3 border rounded-3 bg-light" style="background-color: #FAFCFF !important;">
                <span class="text-secondary small d-block mb-1.5 fw-semibold">Kelengkapan Profil (Profile Completion)</span>
                <div class="d-flex align-items-center gap-3">
                    <div class="progress flex-grow-1" style="height: 10px; border-radius: 5px;">
                        <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $profileCompletion }}%; border-radius: 5px;" aria-valuenow="{{ $profileCompletion }}" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                    <span class="fw-bold text-primary small" style="min-width: 32px;">{{ $profileCompletion }}%</span>
                </div>
            </div>
        </div>

        {{-- Total Favorite Countries --}}
        <div class="col-12 col-md-6">
            <div class="p-3 border rounded-3 bg-light" style="background-color: #FAFCFF !important;">
                <span class="text-secondary small d-block mb-1 fw-semibold">Negara Terpantau (Favorites)</span>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-globe-americas text-primary fs-4"></i>
                    <span class="fs-4 fw-bold text-dark">{{ $totalFavorites }} <span class="small text-secondary fw-normal" style="font-size: 0.85rem;">Negara Terdaftar</span></span>
                </div>
            </div>
        </div>

        {{-- Detail Grid --}}
        <div class="col-12">
            <div class="d-flex flex-column gap-3" style="font-size: 0.875rem;">
                <div class="row border-bottom pb-2">
                    <div class="col-sm-4 text-secondary"><i class="bi bi-activity me-1.5 text-primary"></i>Aktivitas Terakhir:</div>
                    <div class="col-sm-8 text-dark fw-bold">{{ $lastActivity }}</div>
                </div>
                <div class="row border-bottom pb-2">
                    <div class="col-sm-4 text-secondary"><i class="bi bi-box-arrow-in-right me-1.5 text-primary"></i>Login Terakhir:</div>
                    <div class="col-sm-8 text-dark fw-semibold">{{ $lastLogin }}</div>
                </div>
                <div class="row">
                    <div class="col-sm-4 text-secondary"><i class="bi bi-shield-check me-1.5 text-primary"></i>Status Akun:</div>
                    <div class="col-sm-8 text-success fw-bold">● {{ $accountStatus }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
