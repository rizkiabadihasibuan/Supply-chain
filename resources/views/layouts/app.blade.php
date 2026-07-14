<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Control Center') - Global Supply Chain Risk Intelligence</title>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Leaflet.js CSS CDN -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom-dark.css') }}">
    @yield('styles')
</head>
<body>

    <!-- Top Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom sticky-top py-2">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                <span class="fs-4 fw-bold text-glow-cyan">SupplyRisk.io</span>
            </a>
            
            <!-- Global Risk Ticker -->
            <div class="d-none d-md-flex align-items-center ms-4 me-auto flex-grow-1 overflow-hidden" style="max-width: 450px;">
                <span class="badge bg-danger me-2 py-1 blink" style="font-size: 0.75rem;">LIVE</span>
                <marquee class="text-secondary" style="font-size: 0.85rem;" scrollamount="4">
                    DEHAM waiting time increases to 12h &bull; IDTPP heavy rainfall alert &bull; CNSHG congestion rate reaches 68% &bull; EUR/USD volatility low
                </marquee>
            </div>

            <div class="d-flex align-items-center">
                <!-- User Profile & Dropdown -->
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-light dropdown-toggle d-flex align-items-center py-1 px-3 glass-card" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle fs-5 me-2 text-glow-cyan"></i>
                            <div class="text-start me-2 d-none d-sm-block">
                                <small class="d-block text-secondary" style="font-size: 0.7rem; line-height: 1;">{{ Auth::user()->role->name }}</small>
                                <span style="font-size: 0.85rem; font-weight: 500;">{{ Auth::user()->name }}</span>
                            </div>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-dark glass-card mt-2 py-2" aria-labelledby="profileDropdown" style="border-radius: 12px; width: 200px;">
                            <li>
                                <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('profile.edit') }}">
                                    <i class="bi bi-gear me-2 text-secondary"></i> Pengaturan Profil
                                </a>
                            </li>
                            @if(Auth::user()->role->name === 'Admin')
                                <li>
                                    <a class="dropdown-item d-flex align-items-center py-2" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-shield-lock me-2 text-glow-purple"></i> Admin Panel
                                    </a>
                                </li>
                            @endif
                            <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center py-2 text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i> Keluar (Logout)
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth
            </div>
        </div>
    </nav>

    <!-- App Container -->
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-md-3 col-lg-2 sidebar-custom d-md-block collapse p-0" id="sidebarMenu">
                <div class="position-sticky pt-3">
                    <a href="{{ route('dashboard') }}" class="sidebar-link {{ Route::is('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i> Dashboard Utama
                    </a>
                    <a href="{{ route('compare') }}" class="sidebar-link {{ Route::is('compare') ? 'active' : '' }}">
                        <i class="bi bi-arrow-left-right"></i> Comparison Engine
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-exclamation-triangle"></i> Monitoring Risiko
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-water"></i> Pelabuhan Dunia
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-cloud-lightning-rain"></i> Cuaca Ekstrem
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-currency-exchange"></i> Kurs Mata Uang
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-newspaper"></i> Berita Geopolitik
                    </a>
                    <a href="#" class="sidebar-link">
                        <i class="bi bi-star"></i> Watchlist Negara
                    </a>
                    <a href="{{ route('profile.edit') }}" class="sidebar-link {{ Route::is('profile.edit') ? 'active' : '' }}">
                        <i class="bi bi-person-gear"></i> Pengaturan Profil
                    </a>
                </div>
            </div>

            <!-- Main Work Area -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4" style="background-color: var(--bg-primary); min-height: calc(100vh - 56px);">
                
                <!-- Display Flash Message Success -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show glass-card border-success text-success d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Display Flash Message Error -->
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show glass-card border-danger text-danger d-flex align-items-center mb-4" role="alert">
                        <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                        <div>{{ session('error') }}</div>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle with Popper JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Leaflet.js Map CDN -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @yield('scripts')
</body>
</html>
