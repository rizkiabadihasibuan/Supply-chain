{{-- ═══════════════════════════════════════════════════
     ADMIN SIDEBAR COMPONENT – Authentication Flow
     resources/views/components/admin/sidebar.blade.php
     ═══════════════════════════════════════════════════ --}}

<nav id="sidebar" aria-label="Sidebar Admin">
    <div class="sidebar-brand d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-shaded text-primary fs-4 me-2"></i>
            <h5>SupplyChain Admin</h5>
        </div>
        <button class="btn btn-sm d-lg-none text-light" onclick="toggleSidebar()" aria-label="Tutup sidebar">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    <div class="sidebar-menu">
        <a href="{{ route('admin.dashboard') }}" class="menu-item {{ Request::is('admin') || Request::is('admin/dashboard*') ? 'active' : '' }}" aria-label="Buka Dashboard Admin">
            <i class="bi bi-grid-1x2-fill"></i> Dashboard
        </a>
        <a href="{{ route('admin.users') }}" class="menu-item {{ Request::is('admin/users*') ? 'active' : '' }}" aria-label="Kelola Pengguna">
            <i class="bi bi-person-fill-gear"></i> Users
        </a>
        <a href="{{ route('admin.ports') }}" class="menu-item {{ Request::is('admin/ports*') ? 'active' : '' }}" aria-label="Kelola Pelabuhan">
            <i class="bi bi-anchor"></i> Ports
        </a>
        <a href="{{ route('admin.articles') }}" class="menu-item {{ Request::is('admin/articles*') ? 'active' : '' }}" aria-label="Kelola Artikel">
            <i class="bi bi-file-earmark-text"></i> Articles
        </a>
        
        <div class="mt-4 pt-4 border-top border-secondary border-opacity-25">
            <a href="#" class="menu-item text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal" aria-label="Logout">
                <i class="bi bi-box-arrow-right text-danger"></i> Logout
            </a>
        </div>
    </div>
</nav>
