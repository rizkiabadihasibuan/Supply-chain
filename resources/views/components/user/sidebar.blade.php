{{-- ═══════════════════════════════════════════════════
     USER SIDEBAR COMPONENT – Authentication Flow
     resources/views/components/user/sidebar.blade.php
     ═══════════════════════════════════════════════════ --}}

<nav id="sidebar" aria-label="Sidebar Navigasi User">
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-shaded text-primary fs-4 me-2"></i>
            <h5>SupplyChain</h5>
        </div>
        <button class="btn btn-sm d-lg-none text-light" onclick="toggleSidebar()" aria-label="Tutup sidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="sidebar-menu">
        <a href="{{ route('dashboard') }}" class="menu-item {{ Request::is('dashboard') && !Request::is('dashboard/*') ? 'active' : '' }}" aria-label="Buka Dashboard Utama">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('user.countries') }}" class="menu-item {{ Request::is('dashboard/countries*') ? 'active' : '' }}" aria-label="Lihat Data Negara">
            <i class="bi bi-globe2"></i> Negara
        </a>
        <a href="{{ route('user.ports') }}" class="menu-item {{ Request::is('dashboard/ports*') || Request::is('ports*') ? 'active' : '' }}" aria-label="Lihat Data Pelabuhan">
            <i class="bi bi-anchor"></i> Pelabuhan
        </a>
        <a href="{{ route('user.weather') }}" class="menu-item {{ Request::is('dashboard/weather*') ? 'active' : '' }}" aria-label="Lihat Informasi Cuaca">
            <i class="bi bi-cloud-sun-fill"></i> Weather
        </a>
        <a href="{{ route('user.currency') }}" class="menu-item {{ Request::is('dashboard/currency*') ? 'active' : '' }}" aria-label="Lihat Kurs Nilai Tukar">
            <i class="bi bi-cash-stack"></i> Currency
        </a>
        <a href="{{ route('user.news') }}" class="menu-item {{ Request::is('dashboard/news*') ? 'active' : '' }}" aria-label="Membaca Berita Analisis">
            <i class="bi bi-newspaper"></i> News
        </a>
        <a href="{{ route('user.risk') }}" class="menu-item {{ Request::is('dashboard/risk*') ? 'active' : '' }}" aria-label="Lakukan Analisis Risiko">
            <i class="bi bi-exclamation-triangle-fill"></i> Risk Analysis
        </a>
        <a href="{{ route('user.visualization') }}" class="menu-item {{ Request::is('dashboard/visualization*') ? 'active' : '' }}" aria-label="Visualisasi Grafik Data">
            <i class="bi bi-bar-chart-steps"></i> Visualization
        </a>
        <a href="{{ route('user.comparison') }}" class="menu-item {{ Request::is('dashboard/comparison*') ? 'active' : '' }}" aria-label="Perbandingan Lintas Negara">
            <i class="bi bi-arrow-left-right"></i> Comparison
        </a>
        <a href="{{ route('user.favorite') }}" class="menu-item {{ Request::is('dashboard/favorite*') ? 'active' : '' }}" aria-label="Halaman Favorite Watchlist">
            <i class="bi bi-bookmark-star-fill"></i> Favorite
        </a>
        <a href="{{ route('user.profile') }}" class="menu-item {{ Request::is('dashboard/profile*') ? 'active' : '' }}" aria-label="Buka Profil Pengguna">
            <i class="bi bi-person-circle"></i> Profile
        </a>
        
        <div class="mt-4 pt-4 border-top border-secondary border-opacity-25">
            <a href="#" class="menu-item text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal" aria-label="Keluar">
                <i class="bi bi-box-arrow-right text-danger"></i> Logout
            </a>
        </div>
    </div>
</nav>
