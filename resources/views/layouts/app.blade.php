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
            --primary: #2563EB;
            --secondary: #1E293B;
            --background: #0F172A;
            --card-bg: #1E293B;
            --border-color: #334155;
            --success: #22C55E;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #38BDF8;
            --text-primary: #FFFFFF;
            --text-secondary: #CBD5E1;
            --sidebar-bg: #090D1A;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text-secondary);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: var(--background);
        }
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
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
            border-right: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
        }

        .sidebar-brand {
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-color);
            margin-bottom: 1rem;
        }

        .sidebar-brand h5 {
            color: var(--text-primary);
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            overflow-y: auto;
            flex-grow: 1;
            padding-right: 0.25rem;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border-radius: 10px;
            margin-bottom: 0.25rem;
            border: 1px solid transparent;
        }

        .menu-item i {
            font-size: 1.2rem;
            margin-right: 0.75rem;
            color: var(--text-secondary);
            transition: all 0.2s;
        }

        .menu-item:hover {
            background-color: rgba(37, 99, 235, 0.1);
            color: var(--primary);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .menu-item:hover i {
            color: var(--primary);
            transform: translateX(2px);
        }

        .menu-item.active {
            background-color: var(--primary);
            color: var(--text-primary);
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
        }

        .menu-item.active i {
            color: var(--text-primary);
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
            background-color: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 1.5rem;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .search-bar {
            background-color: rgba(30, 41, 59, 0.7);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            padding: 0.5rem 1rem 0.5rem 2.5rem;
            font-size: 0.875rem;
            width: 250px;
            transition: all 0.2s;
        }

        .search-bar:focus {
            background-color: var(--secondary);
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
            outline: none;
            width: 300px;
        }

        .search-wrapper {
            position: relative;
        }

        .search-wrapper i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-secondary);
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem 1.5rem;
        }

        /* Footer Styling */
        footer {
            background-color: var(--sidebar-bg);
            border-top: 1px solid var(--border-color);
            padding: 1.25rem 1.5rem;
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        /* Custom UI Components for Design System */
        .card-custom {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 18px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.1);
            border-color: rgba(37, 99, 235, 0.3);
        }

        .btn-custom {
            border-radius: 10px;
            padding: 0.6rem 1.25rem;
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.2s ease-in-out;
        }

        .btn-custom-primary {
            background-color: var(--primary);
            border: 1px solid var(--primary);
            color: var(--text-primary);
        }

        .btn-custom-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.35);
        }

        .form-control-custom {
            background-color: rgba(30, 41, 59, 0.7);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            color: var(--text-primary);
            padding: 0.6rem 1rem;
            transition: all 0.2s;
        }

        .form-control-custom:focus {
            background-color: var(--secondary);
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2);
            color: var(--text-primary);
            outline: none;
        }

        .table-custom {
            background-color: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
        }

        .table-custom th {
            background-color: rgba(15, 23, 42, 0.5);
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 1rem;
        }

        .table-custom td {
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem;
            background-color: var(--card-bg);
        }

        .table-custom tr:hover td {
            background-color: rgba(255, 255, 255, 0.02) !important;
            color: var(--text-primary);
        }

        /* Badges */
        .badge-custom {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35em 0.8em;
            border-radius: 6px;
            text-transform: uppercase;
        }

        .badge-custom-success {
            background-color: rgba(34, 197, 94, 0.15);
            color: var(--success);
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        .badge-custom-warning {
            background-color: rgba(245, 158, 11, 0.15);
            color: var(--warning);
            border: 1px solid rgba(245, 158, 11, 0.3);
        }

        .badge-custom-danger {
            background-color: rgba(239, 68, 68, 0.15);
            color: var(--danger);
            border: 1px solid rgba(239, 68, 68, 0.3);
        }

        .badge-custom-info {
            background-color: rgba(56, 189, 248, 0.15);
            color: var(--info);
            border: 1px solid rgba(56, 189, 248, 0.3);
        }

        /* Pulse animations for status icons */
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
        /* Override Bootstrap Defaults for seamless dark mode */
        .card {
            background-color: var(--card-bg) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 18px !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15) !important;
            color: var(--text-primary) !important;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 25px rgba(37, 99, 235, 0.1) !important;
            border-color: rgba(37, 99, 235, 0.3) !important;
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
        .table {
            color: var(--text-secondary) !important;
            background-color: var(--card-bg) !important;
        }
        .table th, .table-light th {
            background-color: rgba(15, 23, 42, 0.5) !important;
            color: var(--text-primary) !important;
            border-bottom: 2px solid var(--border-color) !important;
        }
        .table td {
            border-bottom: 1px solid var(--border-color) !important;
            background-color: var(--card-bg) !important;
        }
        .table-hover tbody tr:hover td {
            background-color: rgba(255, 255, 255, 0.02) !important;
            color: var(--text-primary) !important;
        }
        .form-control, .form-select {
            background-color: rgba(30, 41, 59, 0.7) !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 10px !important;
            color: var(--text-primary) !important;
            transition: all 0.2s !important;
        }
        .form-control:focus, .form-select:focus {
            background-color: var(--secondary) !important;
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.2) !important;
            color: var(--text-primary) !important;
            outline: none !important;
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
            <button class="btn btn-sm d-lg-none text-secondary" onclick="toggleSidebar()">
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
                    <span id="live-datetime" class="text-secondary small d-none d-lg-inline-block border border-secondary px-3 py-1.5 rounded-pill bg-dark">
                        <!-- Loaded dynamically via JS -->
                    </span>

                    <!-- Notification Button -->
                    <button class="btn btn-dark btn-sm rounded-circle p-2 position-relative border border-secondary" style="background-color: var(--secondary)">
                        <i class="bi bi-bell text-secondary"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                            3
                            <span class="visually-hidden">notifikasi belum dibaca</span>
                        </span>
                    </button>

                    <div class="vr border-secondary opacity-25"></div>

                    <!-- User Profile Info -->
                    <div class="d-flex align-items-center">
                        <div class="text-end me-2 d-none d-sm-block">
                            <div class="text-light fw-medium small">Administrator</div>
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
