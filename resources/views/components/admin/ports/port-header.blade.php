{{-- ═══════════════════════════════════════════════════
     ADMIN PORT HEADER COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/port-header.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'title' => 'Port Dataset Management',
    'subtitle' => 'Kelola dataset pelabuhan yang digunakan dalam proses monitoring rantai pasok global.',
    'adminName' => 'Administrator',
    'adminAvatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop'
])

<div class="admin-port-header-container flex-wrap g-3">
    <div class="admin-header-left">
        <nav aria-label="Breadcrumb navigasi admin">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin') }}">Admin</a></li>
                <li class="breadcrumb-item active" aria-current="page">Port Dataset</li>
            </ol>
        </nav>
        <h3>{{ $title }}</h3>
        <p>{{ $subtitle }}</p>
    </div>
    
    <div class="admin-port-header-right">
        {{-- Search Port --}}
        <div class="position-relative" style="width: 260px;">
            <input type="text" class="form-control" placeholder="Cari berdasarkan nama pelabuhan, negara, atau kode..." style="min-height: 40px; padding-left: 2.25rem; font-size: 0.85rem;" id="header-port-search" aria-label="Cari pelabuhan global">
            <i class="bi bi-search position-absolute text-secondary" style="left: 14px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
        </div>

        {{-- Notification --}}
        <a href="{{ route('admin.dashboard') }}" class="btn btn-light btn-sm border position-relative" style="min-height: 40px; width: 40px; border-radius: 50% !important; padding:0; display:flex; align-items:center; justify-content:center;" title="Buka Notifikasi" aria-label="Buka list notifikasi sistem">
            <i class="bi bi-bell text-secondary"></i>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem; padding: 0.25em 0.4em;">
                5
            </span>
        </a>

        {{-- Admin Info --}}
        <div class="d-flex align-items-center gap-2 border px-3 py-1.5 rounded-pill bg-white">
            <img src="{{ $adminAvatar }}" alt="Admin Avatar" class="rounded-circle" width="30" height="30" style="object-fit: cover;">
            <div class="d-none d-sm-block text-start">
                <span class="fw-bold text-dark d-block" style="font-size: 0.8rem; line-height: 1.1;">{{ $adminName }}</span>
                <span class="text-secondary" style="font-size: 0.65rem;">System Owner</span>
            </div>
        </div>
    </div>
</div>
