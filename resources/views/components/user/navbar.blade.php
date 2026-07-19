{{-- ═══════════════════════════════════════════════════
     USER NAVBAR COMPONENT – Authentication Flow
     resources/views/components/user/navbar.blade.php
     ═══════════════════════════════════════════════════ --}}

<nav class="navbar navbar-expand-lg navbar-custom" aria-label="Bilah Navigasi Utama">
    <div class="container-fluid p-0">
        <button class="btn btn-outline-primary d-lg-none me-3" onclick="toggleSidebar()" aria-label="Buka navigasi">
            <i class="bi bi-list"></i>
        </button>
        
        <span class="navbar-brand text-primary fw-bold d-none d-lg-block me-4">
            <i class="bi bi-shield-shaded me-1"></i> Control Tower
        </span>

        <!-- Search Column -->
        <div class="search-wrapper d-none d-md-block">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari data, pelabuhan, negara..." class="search-bar" aria-label="Cari global">
        </div>

        <div class="ms-auto d-flex align-items-center gap-3">
            <!-- Date & Time Widget -->
            <span id="live-datetime" class="text-secondary small d-none d-lg-inline-block border px-3 py-1.5 rounded-pill bg-light">
                <!-- Loaded dynamically via JS -->
            </span>

            <!-- Notification Button -->
            <button class="btn btn-light btn-sm rounded-circle p-2 position-relative border" style="background-color: #F8FAFC" aria-label="Notifikasi">
                <i class="bi bi-bell text-secondary"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                    3
                    <span class="visually-hidden">notifikasi belum dibaca</span>
                </span>
            </button>

            <div class="vr border-secondary opacity-25"></div>

            <!-- User Profile Info -->
            <a href="{{ route('user.profile') }}" class="d-flex align-items-center text-decoration-none text-dark hover-opacity" style="cursor: pointer;" aria-label="Buka Profil & Pengaturan">
                <div class="text-end me-2 d-none d-sm-block">
                    <div class="text-dark fw-medium small">{{ Auth::user()->name ?? 'User' }}</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">{{ Auth::user()->email ?? '' }}</div>
                </div>
                <div class="rounded-circle border border-primary d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg,#2563EB,#06B6D4);color:#fff;font-weight:700;font-size:.85rem;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                </div>
            </a>
        </div>
    </div>
</nav>
