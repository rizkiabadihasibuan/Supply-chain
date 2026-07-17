<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SupplyChain Platform')</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2563EB;
            --background: #F8FAFC;
            --card-bg: #FFFFFF;
            --border-color: #E2E8F0;
            --success: #22C55E;
            --warning: #F59E0B;
            --danger: #EF4444;
            --info: #06B6D4;
            --sidebar-bg: #123458;
            --text-primary: #1E293B;
            --text-secondary: #64748B;
            --sidebar-width: 260px;
            --radius-custom: 16px;
        }

        body {
            font-family: 'Inter', 'Poppins', sans-serif;
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
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
        }

        .menu-item.active i {
            color: #FFFFFF;
        }

        /* Sidebar Overlay */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background-color: rgba(18, 52, 88, 0.4);
            backdrop-filter: blur(4px);
            z-index: 95;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
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
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.15);
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
            border-color: rgba(37, 99, 235, 0.2) !important;
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
            min-height: 44px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .btn-primary {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #FFFFFF !important;
        }

        .btn-primary:hover {
            background-color: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25) !important;
        }

        .btn-success {
            background-color: var(--success) !important;
            border-color: var(--success) !important;
            color: #FFFFFF !important;
        }

        .btn-success:hover {
            background-color: #16a34a !important;
            border-color: #16a34a !important;
            box-shadow: 0 4px 12px rgba(34, 197, 94, 0.25) !important;
        }

        .btn-danger {
            background-color: var(--danger) !important;
            border-color: var(--danger) !important;
            color: #FFFFFF !important;
        }

        .btn-danger:hover {
            background-color: #dc2626 !important;
            border-color: #dc2626 !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.25) !important;
        }

        .btn-warning {
            background-color: var(--warning) !important;
            border-color: var(--warning) !important;
            color: #FFFFFF !important;
        }

        .btn-warning:hover {
            background-color: #d97706 !important;
            border-color: #d97706 !important;
            box-shadow: 0 4px 12px rgba(245, 158, 11, 0.25) !important;
        }

        .btn-info {
            background-color: var(--info) !important;
            border-color: var(--info) !important;
            color: #FFFFFF !important;
        }

        .btn-info:hover {
            background-color: #0891b2 !important;
            border-color: #0891b2 !important;
            box-shadow: 0 4px 12px rgba(6, 182, 212, 0.25) !important;
        }

        .btn-light {
            background-color: #F1F5F9 !important;
            border-color: #E2E8F0 !important;
            color: var(--text-primary) !important;
        }

        .btn-light:hover {
            background-color: #E2E8F0 !important;
            border-color: #CBD5E1 !important;
        }

        .btn-outline-primary {
            color: var(--primary) !important;
            border-color: var(--primary) !important;
            background-color: transparent !important;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary) !important;
            color: #FFFFFF !important;
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

        /* Mobile Table Card Transformation */
        @media (max-width: 767.98px) {
            .table-responsive-card table, 
            .table-responsive-card thead, 
            .table-responsive-card tbody, 
            .table-responsive-card th, 
            .table-responsive-card td, 
            .table-responsive-card tr {
                display: block;
                width: 100%;
            }
            .table-responsive-card thead {
                display: none !important;
            }
            .table-responsive-card tr {
                background: var(--card-bg);
                border: 1px solid var(--border-color);
                border-radius: var(--radius-custom);
                padding: 1.25rem;
                margin-bottom: 1.25rem;
                box-shadow: 0 4px 18px rgba(18, 52, 88, 0.02);
            }
            .table-responsive-card td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px dashed var(--border-color) !important;
                padding: 0.75rem 0 !important;
                text-align: right;
                font-size: 0.875rem;
            }
            .table-responsive-card td:last-child {
                border-bottom: none !important;
            }
            .table-responsive-card td::before {
                content: attr(data-label);
                font-weight: 600;
                text-transform: uppercase;
                font-size: 0.7rem;
                color: var(--text-secondary);
                text-align: left;
                margin-right: 1.5rem;
            }
            .table-responsive-card td .btn {
                width: auto;
                min-height: 38px;
                height: 38px;
                padding: 0.25rem 1rem;
            }
        }

        /* Form Inputs */
        .form-control, .form-select {
            background-color: #FFFFFF !important;
            border: 1px solid var(--border-color) !important;
            border-radius: 10px !important;
            color: var(--text-primary) !important;
            padding: 0.55rem 1rem !important;
            transition: all 0.2s !important;
            min-height: 44px;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
            outline: none !important;
            color: var(--text-primary) !important;
        }

        .form-control::placeholder {
            color: #94A3B8;
        }

        /* Status Badges */
        .badge {
            font-weight: 600;
            font-size: 0.725rem;
            padding: 0.4em 0.85em;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
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
            background-color: rgba(6, 182, 212, 0.12);
            color: var(--info);
            border: 1px solid rgba(6, 182, 212, 0.25);
        }

        .badge-primary {
            background-color: rgba(37, 99, 235, 0.12);
            color: var(--primary);
            border: 1px solid rgba(37, 99, 235, 0.25);
        }

        /* Modal Custom Style */
        .modal-content {
            border-radius: var(--radius-custom) !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 15px 35px rgba(18, 52, 88, 0.12) !important;
            overflow: hidden;
        }

        .modal-header {
            border-bottom: 1px solid var(--border-color) !important;
            background-color: #F8FAFC !important;
            padding: 1.25rem 1.5rem !important;
            border-top-left-radius: var(--radius-custom) !important;
            border-top-right-radius: var(--radius-custom) !important;
        }

        .modal-footer {
            border-top: 1px solid var(--border-color) !important;
            background-color: #F8FAFC !important;
            border-bottom-left-radius: var(--radius-custom) !important;
            border-bottom-right-radius: var(--radius-custom) !important;
            padding: 1rem 1.5rem !important;
        }

        /* Dropdown Custom Style */
        .dropdown-menu {
            border-radius: 12px !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 8px 24px rgba(18, 52, 88, 0.08) !important;
            padding: 0.5rem !important;
            background-color: #FFFFFF;
        }

        .dropdown-item {
            border-radius: 8px !important;
            padding: 0.55rem 1rem !important;
            font-size: 0.875rem !important;
            color: var(--text-primary) !important;
            transition: all 0.2s !important;
            min-height: 40px;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: #F1F5F9 !important;
            color: var(--primary) !important;
        }

        /* Pagination Custom Style */
        .pagination {
            display: flex;
            gap: 0.35rem;
            margin-bottom: 0;
        }

        .page-item .page-link {
            border: 1px solid var(--border-color);
            color: var(--text-secondary);
            border-radius: 8px !important;
            padding: 0.5rem 0.9rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            min-height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .page-item:first-child .page-link, .page-item:last-child .page-link {
            border-radius: 8px !important;
        }

        .page-item .page-link:hover {
            background-color: #F1F5F9;
            color: var(--primary);
            border-color: var(--border-color);
        }

        .page-item.active .page-link {
            background-color: var(--primary) !important;
            border-color: var(--primary) !important;
            color: #FFFFFF !important;
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2);
        }

        .page-item.disabled .page-link {
            background-color: #F8FAFC;
            color: #CBD5E1;
            border-color: var(--border-color);
        }

        /* Breadcrumb Custom Style */
        .breadcrumb {
            background-color: transparent !important;
            padding: 0 !important;
            margin-bottom: 0.5rem !important;
        }

        .breadcrumb-item {
            font-size: 0.85rem;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .breadcrumb-item a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: var(--primary);
        }

        .breadcrumb-item.active {
            color: var(--text-primary);
            font-weight: 600;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #94A3B8;
            font-family: "bootstrap-icons";
            content: "\F285" !important;
            font-size: 0.65rem;
            vertical-align: middle;
            margin: 0 0.5rem;
        }

        /* Alert Custom Style */
        .alert {
            border-radius: 12px !important;
            border: 1px solid transparent !important;
            font-size: 0.875rem !important;
            padding: 1rem 1.25rem !important;
        }

        .alert-success {
            background-color: rgba(34, 197, 94, 0.06) !important;
            border-color: rgba(34, 197, 94, 0.15) !important;
            color: #15803d !important;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.06) !important;
            border-color: rgba(239, 68, 68, 0.15) !important;
            color: #b91c1c !important;
        }

        .alert-warning {
            background-color: rgba(245, 158, 11, 0.06) !important;
            border-color: rgba(245, 158, 11, 0.15) !important;
            color: #c2410c !important;
        }

        .alert-info {
            background-color: rgba(6, 182, 212, 0.06) !important;
            border-color: rgba(6, 182, 212, 0.15) !important;
            color: #0e7490 !important;
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

        /* Smooth Transitions */
        .fade-in-up {
            animation: fadeInUp 0.4s ease-out forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
            <a href="{{ route('design-system') }}" class="menu-item {{ Request::is('design-system') ? 'active' : '' }}">
                <i class="bi bi-palette-fill"></i> Design System
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
            <a href="{{ route('monitoring') }}" class="menu-item {{ Request::is('monitoring') ? 'active' : '' }}">
                <i class="bi bi-bookmark-star-fill"></i> Monitoring Center
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

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

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
                    <a href="{{ route('notifications') }}" class="btn btn-light btn-sm rounded-circle p-2 position-relative border" style="background-color: #F8FAFC" aria-label="Buka Notifikasi">
                        <i class="bi bi-bell text-secondary"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger text-light" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                            3
                            <span class="visually-hidden">notifikasi belum dibaca</span>
                        </span>
                    </a>

                    <div class="vr border-secondary opacity-25"></div>

                    <!-- User Profile Info -->
                    <a href="{{ route('profile') }}" class="d-flex align-items-center text-decoration-none text-dark hover-opacity" style="cursor: pointer;" aria-label="Buka Profil & Pengaturan">
                        <div class="text-end me-2 d-none d-sm-block">
                            <div class="text-dark fw-medium small">Administrator</div>
                            <div class="text-secondary" style="font-size: 0.75rem;">Command Center</div>
                        </div>
                        <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop" alt="Profil User" class="rounded-circle border border-primary" width="36" height="36" style="object-fit: cover;">
                    </a>
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
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            sidebar.classList.toggle('show');
            if (sidebar.classList.contains('show')) {
                overlay.classList.add('show');
            } else {
                overlay.classList.remove('show');
            }
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
