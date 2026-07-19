{{-- ═══════════════════════════════════════════════════
     ADMIN NAVBAR COMPONENT – Authentication Flow
     resources/views/components/admin/navbar.blade.php
     ═══════════════════════════════════════════════════ --}}

<nav class="navbar navbar-expand-lg navbar-custom" aria-label="Bilah Navigasi Admin">
    <div class="container-fluid p-0">
        <button class="btn btn-outline-primary d-lg-none me-3" onclick="toggleSidebar()" aria-label="Buka navigasi">
            <i class="bi bi-list"></i>
        </button>
        
        <span class="navbar-brand text-primary fw-bold d-none d-lg-block me-4">
            <i class="bi bi-shield-shaded me-1"></i> Admin Command Center
        </span>

        <!-- Search Column -->
        <div class="search-wrapper d-none d-md-block">
            <i class="bi bi-search"></i>
            <input type="text" placeholder="Cari berdasarkan judul artikel..." class="search-bar" id="header-port-search" aria-label="Cari artikel global">
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

            <!-- Admin Profile Info -->
            <div class="d-flex align-items-center text-decoration-none text-dark" style="cursor: default;">
                <div class="text-end me-2 d-none d-sm-block">
                    <div class="text-dark fw-medium small">{{ Auth::user()->name ?? 'Administrator' }}</div>
                    <div class="text-secondary" style="font-size: 0.75rem;">{{ Auth::user()->email ?? '' }}</div>
                </div>
                <div class="rounded-circle border border-primary d-flex align-items-center justify-content-center" style="width:36px;height:36px;background:linear-gradient(135deg,#DC2626,#F59E0B);color:#fff;font-weight:700;font-size:.85rem;">
                    {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </div>
    </div>
</nav>
