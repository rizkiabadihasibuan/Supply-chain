<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Portal - SupplyChain')</title>
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
            --success: #16A34A;
            --warning: #F59E0B;
            --danger: #DC2626;
            --info: #0EA5E9;
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

        /* Main Wrapper */
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

    <!-- Sidebar Admin Component -->
    <x-admin.sidebar />

    <!-- Sidebar Overlay for Mobile -->
    <div id="sidebar-overlay" class="sidebar-overlay" onclick="toggleSidebar()"></div>

    <!-- Main Wrapper -->
    <div id="main-wrapper">
        <!-- Top Navbar Admin Component -->
        <x-admin.navbar />

        <!-- Content Area -->
        <main class="content-area">
            @yield('content')
        </main>

        <!-- Footer Admin Component -->
        <x-admin.footer />
    </div>

    <x-logout-modal />

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
    </script>
    @yield('scripts')
</body>
</html>
