<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SupplyChain Platform')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0EA5E9;
            --background: #F7F9FC;
            --card-bg: #FFFFFF;
            --border-color: #E5E7EB;
            --success: #22C55E;
            --warning: #F59E0B;
            --danger: #EF4444;
            --sidebar-bg: #123458;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --sidebar-width: 260px;
            --radius-custom: 16px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-primary);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--background);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary);
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            background-color: var(--sidebar-bg);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            box-shadow: 4px 0 15px rgba(18, 52, 88, 0.05);
        }

        .sidebar-brand {
            padding-bottom: 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 1rem;
        }

        .sidebar-brand h5 {
            color: #FFFFFF;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            overflow-y: auto;
            flex-grow: 1;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: #E2E8F0;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border-radius: 12px;
            margin-bottom: 0.25rem;
        }

        .menu-item i {
            font-size: 1.15rem;
            margin-right: 0.75rem;
            color: #94A3B8;
            transition: all 0.2s;
        }

        .menu-item:hover {
            background-color: rgba(255, 255, 255, 0.08);
            color: #FFFFFF;
        }

        .menu-item:hover i {
            color: var(--primary);
        }

        .menu-item.active {
            background-color: var(--primary);
            color: #FFFFFF;
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
        }

        .menu-item.active i {
            color: #FFFFFF;
        }

        /* Main Content Wrapper */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .search-bar {
            background-color: #F1F5F9;
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            padding: 0.45rem 1rem 0.45rem 2.25rem;
            font-size: 0.85rem;
            width: 240px;
            transition: all 0.2s;
        }

        .search-bar:focus {
            background-color: #FFFFFF;
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(14, 165, 233, 0.15);
            outline: none;
            width: 280px;
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 0.85rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
            font-size: 0.85rem;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem 1.5rem;
        }

        /* Footer Styling */
        footer {
            background-color: #FFFFFF;
            border-top: 1px solid var(--border-color);
            padding: 1.15rem 1.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Overrides Bootstrap Defaults for Premium Custom Corporate Aesthetics */
        .card {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: var(--radius-custom) !important;
            box-shadow: 0 4px 18px rgba(18, 52, 88, 0.03) !important;
            color: var(--text-primary) !important;
            transition: all 0.25s ease-in-out;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 22px rgba(18, 52, 88, 0.06) !important;
            border-color: rgba(14, 165, 233, 0.25) !important;
        }

        .bg-white {
            background-color: var(--card-bg) !important;
        }

        .text-dark {
            color: var(--text-primary) !important;
        }

        .text-muted {
            color: var(--text-secondary) !important;
        }

        /* Button Styling */
        .btn {
            border-radius: 10px;
            font-weight: 500;
            padding: 0.55rem 1.15rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #FFFFFF !important;
        }

        .btn-primary:hover {
            background-color: #0284c7 !important;
            border-color: #0284c7 !important;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.3) !important;
        }

        /* Table Styling */
        .table {
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .table th {
            background-color: #F8FAFC !important;
            color: var(--text-primary) !important;
            font-weight: 600;
            border-bottom: 2px solid var(--border-color) !important;
            padding: 0.9rem 1rem !important;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .table td {
            padding: 0.9rem 1rem !important;
            border-bottom: 1px solid var(--border-color) !important;
            background-color: var(--card-bg) !important;
            vertical-align: middle;
        }

        .table-hover tbody tr:hover td {
            background-color: #F8FAFC !important;
            color: var(--text-primary) !important;
        }

        /* Form Inputs */
        .form-control, .form-select {
            background-color: #FFFFFF !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 10px !important;
            color: var(--text-primary) !important;
            padding: 0.55rem 1rem !important;
            transition: all 0.2s !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.12) !important;
            outline: none !important;
            color: var(--text-primary) !important;
        }

        /* Status Badges */
        .badge {
            font-weight: 600;
            font-size: 0.75rem;
            padding: 0.35em 0.8em;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: rgba(34, 197, 94, 0.12);
            color: var(--success);
            border: 1px solid rgba(34, 197, 94, 0.25);
        }

        .badge-warning {
            background-color: rgba(245, 158, 11, 0.12);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.25);
        }

        .badge-danger {
            background-color: rgba(239, 68, 68, 0.12);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.25);
        }

        .badge-info {
            background-color: rgba(14, 165, 233, 0.12);
            color: var(--primary);
            border: 1px solid rgba(14, 165, 233, 0.25);
        }

        /* Modal Custom Style */
        .modal-content {
            border-radius: var(--radius-custom) !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 10px 30px rgba(18, 52, 88, 0.1) !important;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color) !important;
            background-color: #F8FAFC !important;
            border-top-left-radius: var(--radius-custom) !important;
            border-top-right-radius: var(--radius-custom) !important;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color) !important;
            background-color: #F8FAFC !important;
            border-bottom-left-radius: var(--radius-custom) !important;
            border-bottom-right-radius: var(--radius-custom) !important;
        }

        /* Dropdown Custom Style */
        .dropdown-menu {
            border-radius: 12px !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 8px 24px rgba(18, 52, 88, 0.08) !important;
            padding: 0.5rem !important;
        }

        .dropdown-item {
            border-radius: 8px !important;
            padding: 0.5rem 1rem !important;
            font-size: 0.85rem !important;
            color: var(--text-primary) !important;
            transition: all 0.2s !important;
        }

        .dropdown-item:hover {
            background-color: #F1F5F9 !important;
            color: var(--text-primary) !important;
        }

        /* Alert Custom Style */
        .alert {
            border-radius: 12px !important;
            border: 1px solid transparent !important;
            font-size: 0.875rem !important;
        }

        .alert-success {
            background-color: rgba(34, 197, 94, 0.08) !important;
            border-color: rgba(34, 197, 94, 0.15) !important;
            color: #166534 !important;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.08) !important;
            border-color: rgba(239, 68, 68, 0.15) !important;
            color: #991b1b !important;
        }

        .alert-warning {
            background-color: rgba(245, 158, 11, 0.08) !important;
            border-color: rgba(245, 158, 11, 0.15) !important;
            color: #9a3412 !important;
        }

        /* Pulse Indicator */
        .pulse-indicator {
            position: relative;
            display: inline-block;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: var(--success);
            margin-right: 8px;
        }

        .pulse-indicator::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background-color: var(--success);
            animation: pulse-ring 1.5s infinite;
        }

        @keyframes pulse-ring {
            0% {
                transform: scale(0.95);
                opacity: 0.8;
            }
            100% {
                transform: scale(2.5);
                opacity: 0;
            }
        }

        /* Responsive Sidebar */
        @media (max-width: 991.98px) {
            #sidebar {
                left: calc(-1 * var(--sidebar-width));
            }
            #sidebar.show {
                left: 0;
            }
            #main-wrapper {
                margin-left: 0;
            }
        }
    </style>
    @yield('styles')
</head>
<body>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-brand d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-shield-shaded text-primary fs-4 me-2"></i>
                <h5>SupplyChain</h5>
            </div>
            <button class="btn btn-sm d-lg-none text-light" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('countries') }}" class="menu-item {{ Request::is('countries') ? 'active' : '' }}">
                <i class="bi bi-globe2"></i> Negara
            </a>
            <a href="{{ route('weather') }}" class="menu-item {{ Request::is('weather') ? 'active' : '' }}">
                <i class="bi bi-cloud-sun-fill"></i> Cuaca
            </a>
            <a href="{{ route('currency') }}" class="menu-item {{ Request::is('currency') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Nilai Tukar
            </a>
            <a href="{{ route('ports') }}" class="menu-item {{ Request::is('ports') ? 'active' : '' }}">
                <i class="bi bi-anchor"></i> Pelabuhan
            </a>
            <a href="{{ route('news') }}" class="menu-item {{ Request::is('news') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> Berita
            </a>
            <a href="{{ route('risk') }}" class="menu-item {{ Request::is('risk') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> Analisis Risiko
            </a>
            <a href="{{ route('comparison') }}" class="menu-item {{ Request::is('comparison') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Perbandingan Negara
            </a>
            <a href="{{ route('watchlist') }}" class="menu-item {{ Request::is('watchlist') ? 'active' : '' }}">
                <i class="bi bi-bookmark-star-fill"></i> Daftar Pantauan
            </a>
            <a href="#" class="menu-item">
                <i class="bi bi-journal-text"></i> Artikel
            </a>
            <a href="{{ route('admin') }}" class="menu-item {{ Request::is('admin') ? 'active' : '' }}">
                <i class="bi bi-shield-lock-fill"></i> Administrasi
            </a>
            <a href="#" class="menu-item">
                <i class="bi bi-gear-fill"></i> Pengaturan
            </a>
        </div>
    </nav>

    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-lg navbar-custom">
            <div class="container-fluid p-0">
                <button class="btn btn-outline-primary d-lg-none me-3" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                
                <span class="navbar-brand text-primary fw-bold d-none d-lg-block me-4">
                    <i class="bi bi-shield-shaded me-1"></i> Control Tower
                </span>

                <!-- Search Column -->
                <div class="search-wrapper d-none d-md-block">
                    <i class="bi bi-search"></i>
                    <input type="text" placeholder="Cari data, pelabuhan, negara..." class="search-bar">
                </div>

                <div class="ms-auto d-flex align-items-center gap-3">
                    <!-- Date & Time Widget -->
                    <span id="live-datetime" class="text-secondary small d-none d-lg-inline-block border px-3 py-1.5 rounded-pill bg-light">
                        <!-- Loaded dynamically via JS -->
                    </span>

                    <!-- Notification Button -->
                    <button class="btn btn-light btn-sm rounded-circle p-2 position-relative border" style="background-color: #F8FAFC">
                        <i class="bi bi-bell text-secondary"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                            3
                            <span class="visually-hidden">notifikasi belum dibaca</span>
                        </span>
                    </button>

                    <div class="vr border-secondary opacity-25"></div>

                    <!-- User Profile Info -->
                    <div class="d-flex align-items-center">
                        <div class="text-end me-2 d-none d-sm-block">
                            <div class="text-dark fw-medium small">Administrator</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">Command Center</div>
                        </div>
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" alt="Profil User" class="rounded-circle border border-primary" width="36" height="36" style="object-fit: cover;">
                    </div>
                </div>
            </div>
        </nav>

        <!-- Content Area -->
        <main class="content-area">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="text-center text-md-start">
            <div class="container-fluid p-0 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <span>&copy; {{ date('Y') }} <strong>Platform SupplyChain</strong>. Hak Cipta Dilindungi.</span>
                <span class="mt-2 mt-md-0">Siap Enterprise | v1.0</span>
            </div>
        </footer>
    </div>

    <!-- Bootstrap Bundle with Popper JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Global AJAX and Sidebar JS -->
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Live Real-time Clock
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
            const dateStr = now.toLocaleDateString('id-ID', options);
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
            const datetimeEl = document.getElementById('live-datetime');
            if (datetimeEl) {
                datetimeEl.innerHTML = `<span class="pulse-indicator"></span>${dateStr} | ${timeStr}`;
            }
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();

        // Global Enterprise API AJAX Wrapper using Fetch API
        window.SupplyChainAPI = {
            async fetch(endpoint, options = {}) {
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                
                const defaultHeaders = {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                };
                
                if (csrfToken) {
                    defaultHeaders['X-CSRF-TOKEN'] = csrfToken;
                }

                const config = {
                    ...options,
                    headers: {
                        ...defaultHeaders,
                        ...(options.headers || {})
                    }
                };

                try {
                    const response = await fetch(`/api/${endpoint.replace(/^\//, '')}`, config);
                    const data = await response.json();
                    
                    if (!response.ok) {
                        throw new Error(data.message || `API error with status ${response.status}`);
                    }
                    
                    return data;
                } catch (error) {
                    console.error(`[SupplyChain API Error] Fetch failed for /api/${endpoint}:`, error);
                    throw error;
                }
            }
        };
    </script>
    @yield('scripts')
</body>
</html>
