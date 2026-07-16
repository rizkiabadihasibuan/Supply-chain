<!DOCTYPE html>
<html lang="en">
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
            --primary-blue: #0d6efd;
            --dark-blue: #0a4ebd;
            --light-blue: #e7f1ff;
            --gray-bg: #f8f9fa;
            --gray-border: #e9ecef;
            --text-muted: #6c757d;
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--gray-bg);
            color: #212529;
            overflow-x: hidden;
        }

        /* Sidebar Styling */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 100;
            background-color: #ffffff;
            border-right: 1px solid var(--gray-border);
            transition: all 0.3s;
        }

        .sidebar-brand {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid var(--gray-border);
        }

        .sidebar-brand h5 {
            color: var(--primary-blue);
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
        }

        .sidebar-menu {
            padding: 1rem 0;
            overflow-y: auto;
            height: calc(100vh - 75px);
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 0.75rem 1.25rem;
            color: #495057;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.925rem;
            transition: all 0.2s ease-in-out;
            border-left: 4px solid transparent;
        }

        .menu-item i {
            font-size: 1.1rem;
            margin-right: 0.75rem;
            color: var(--text-muted);
            transition: all 0.2s;
        }

        .menu-item:hover {
            background-color: var(--gray-bg);
            color: var(--primary-blue);
            border-left-color: var(--primary-blue);
        }

        .menu-item:hover i {
            color: var(--primary-blue);
        }

        .menu-item.active {
            background-color: var(--light-blue);
            color: var(--primary-blue);
            border-left-color: var(--primary-blue);
            font-weight: 600;
        }

        .menu-item.active i {
            color: var(--primary-blue);
        }

        /* Main Content Wrapper */
        #main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: all 0.3s;
        }

        /* Navbar Styling */
        .navbar-custom {
            background-color: #ffffff;
            border-bottom: 1px solid var(--gray-border);
            padding: 0.9rem 1.5rem;
        }

        /* Content Area */
        .content-area {
            flex: 1;
            padding: 2rem 1.5rem;
        }

        /* Footer Styling */
        footer {
            background-color: #ffffff;
            border-top: 1px solid var(--gray-border);
            padding: 1rem 1.5rem;
            font-size: 0.85rem;
            color: var(--text-muted);
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
            <button class="btn btn-sm d-lg-none" onclick="toggleSidebar()">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
        <div class="sidebar-menu">
            <a href="{{ route('dashboard') }}" class="menu-item {{ Request::is('/') || Request::is('dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
            <a href="{{ route('countries') }}" class="menu-item {{ Request::is('countries') ? 'active' : '' }}">
                <i class="bi bi-globe2"></i> Countries
            </a>
            <a href="{{ route('weather') }}" class="menu-item {{ Request::is('weather') ? 'active' : '' }}">
                <i class="bi bi-cloud-sun-fill"></i> Weather
            </a>
            <a href="{{ route('currency') }}" class="menu-item {{ Request::is('currency') ? 'active' : '' }}">
                <i class="bi bi-cash-stack"></i> Currency
            </a>
            <a href="{{ route('ports') }}" class="menu-item {{ Request::is('ports') ? 'active' : '' }}">
                <i class="bi bi-anchor"></i> Ports
            </a>
            <a href="{{ route('news') }}" class="menu-item {{ Request::is('news') ? 'active' : '' }}">
                <i class="bi bi-newspaper"></i> News
            </a>
            <a href="{{ route('risk') }}" class="menu-item {{ Request::is('risk') ? 'active' : '' }}">
                <i class="bi bi-exclamation-triangle-fill"></i> Risk Analysis
            </a>
            <a href="{{ route('comparison') }}" class="menu-item {{ Request::is('comparison') ? 'active' : '' }}">
                <i class="bi bi-arrow-left-right"></i> Comparison
            </a>
            <a href="{{ route('watchlist') }}" class="menu-item {{ Request::is('watchlist') ? 'active' : '' }}">
                <i class="bi bi-bookmark-star-fill"></i> Watchlist
            </a>
            <a href="{{ route('admin') }}" class="menu-item {{ Request::is('admin') ? 'active' : '' }}">
                <i class="bi bi-gear-fill"></i> Admin
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
                <span class="navbar-text fw-semibold text-dark fs-5">
                    @yield('header_title', 'Dashboard')
                </span>
                <div class="ms-auto d-flex align-items-center">
                    <span class="text-muted d-none d-sm-inline-block me-3">
                        <i class="bi bi-clock me-1"></i> System Online
                    </span>
                    <div class="vr me-3 d-none d-sm-block"></div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill">
                        v1.0-Foundation
                    </span>
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
                <span>&copy; {{ date('Y') }} <strong>SupplyChain Platform</strong>. All rights reserved.</span>
                <span class="mt-2 mt-md-0">Enterprise Ready | v1.0</span>
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
