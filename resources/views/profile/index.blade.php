{{-- ═══════════════════════════════════════════════════
     USER PROFILE & SETTINGS INDEX – Milestone 3.14
     resources/views/profile/index.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.user.app')

@section('title', 'User Profile & Settings - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/profile/profile.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile/tabs.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile/form.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile/responsive.css') }}">
@endsection

@section('content')
    <!-- Page Header Component -->
    <x-page-header 
        title="User Profile & Settings" 
        subtitle="Kelola informasi akun, keamanan, preferensi aplikasi, dan aktivitas pengguna." 
        :breadcrumbs="['Profile & Settings' => '#']" 
    />

    <!-- FEEDBACK AREA -->
    <div id="settings-feedback-area" style="display: none;"></div>

    <!-- Skeleton Loading wrapper (Simulasi) -->
    <div id="skeleton-container" class="mb-4">
        <x-loading-state type="card" count="1" height="200px" />
        <x-loading-state type="card" count="1" height="400px" />
    </div>

    <!-- Error State Component (Simulasi/Sembunyi by default) -->
    <div id="error-state-container" class="mb-4" style="display: none;">
        <x-error-state 
            title="Gagal Memuat Pengaturan." 
            description="Satelit data profil pengguna tidak terhubung. Silakan coba kembali." 
            onclick="UserProfile.retryLoad()" 
        />
    </div>

    <!-- Main Content Area (Disembunyikan saat loading skeleton) -->
    <div id="main-content-grid" style="display: none;" class="flex-column gap-4">
        
        {{-- Profile Summary Card --}}
        <x-profile-summary />

        {{-- Profile Navigation Tabs --}}
        <x-profile-tabs />

        {{-- Content Area Sheets --}}
        <div class="profile-content-sheets">
            
            {{-- TAB 1: OVERVIEW --}}
            <div class="settings-tab-sheet" id="sheet-overview">
                <x-overview-card />
            </div>

            {{-- TAB 2: PERSONAL INFORMATION --}}
            <div class="settings-tab-sheet" id="sheet-edit-profile" style="display: none;">
                <x-profile-form />
            </div>

            {{-- TAB 3: SECURITY --}}
            <div class="settings-tab-sheet" id="sheet-security" style="display: none;">
                <x-security-panel />
            </div>

            {{-- TAB 4: NOTIFICATIONS --}}
            <div class="settings-tab-sheet" id="sheet-notification" style="display: none;">
                <x-notification-panel />
            </div>

            {{-- TAB 5: PREFERENCES --}}
            <div class="settings-tab-sheet" id="sheet-preferences" style="display: none;">
                <x-preferences-panel />
            </div>

            {{-- TAB 6: ACTIVITY LOG --}}
            <div class="settings-tab-sheet" id="sheet-activity" style="display: none;">
                <x-activity-table />
            </div>

        </div>

    </div>

    {{-- Avatar Upload Modal --}}
    <x-avatar-modal />

    {{-- Tombol Simulator Error & Reset di bagian bawah untuk kemudahan testing UI --}}
    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
        <button class="btn btn-sm btn-outline-danger" onclick="UserProfile.simulateError()">
            ⚡ Simulasikan Error Layanan
        </button>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/profile/profile.js') }}"></script>
    <script src="{{ asset('js/profile/tabs.js') }}"></script>
    <script src="{{ asset('js/profile/avatar.js') }}"></script>
    <script src="{{ asset('js/profile/security.js') }}"></script>
    <script src="{{ asset('js/profile/preferences.js') }}"></script>
@endsection
