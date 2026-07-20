{{-- ═══════════════════════════════════════════════════
     ADMIN USER MANAGEMENT INDEX – Milestone 3.16A
     resources/views/pages/admin/users/index.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'User Management - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/users/users.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/users/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/users/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/users/responsive.css') }}">
@endsection

@section('content')
    <div class="admin-users-wrapper">
        {{-- ══════ HEADER ══════ --}}
        <x-admin-user-header />

        {{-- Container Error Simulator --}}
        <div id="users-error-container" style="display: none;" class="mb-2">
            <div class="alert alert-danger d-flex align-items-center justify-content-between p-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-0.5">Terjadi kesalahan saat memuat data pengguna.</h6>
                        <span class="small">Koneksi ke database replikasi otentikasi terputus. Silakan coba kembali.</span>
                    </div>
                </div>
                <button class="btn btn-sm btn-danger border-white border" onclick="UsersCore.retryFromError()">Coba Lagi</button>
            </div>
        </div>

        {{-- Skeleton Loading Container (Hidden) --}}
        <div id="users-skeleton-container" style="display: none;" class="row g-4">
            <div class="col-12">
                <x-loading-state type="card" count="4" height="100px" />
                <x-loading-state type="card" count="1" height="400px" />
            </div>
        </div>

        {{-- ══════ MAIN CONTENT AREA (Direct Display) ══════ --}}
        <div id="users-main-content">
            
            {{-- ══════ ACTION TOOLBAR ══════ --}}
            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                <div class="btn-group" role="group" aria-label="Toolbar Aksi">
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#userCreateModal" style="min-height: 38px; font-size: 0.85rem;">
                        <i class="bi bi-person-plus-fill me-1.5"></i> Tambah User
                    </button>
                    <button class="btn btn-outline-secondary" onclick="UsersCore.showToast('Mengekspor data ke Excel (Simulasi)...')" style="min-height: 38px; font-size: 0.85rem;">
                        <i class="bi bi-file-earmark-spreadsheet me-1.5"></i> Export Data
                    </button>
                    <button class="btn btn-outline-secondary" onclick="UsersCore.showToast('Membuka dialog impor CSV (Simulasi)...')" style="min-height: 38px; font-size: 0.85rem;">
                        <i class="bi bi-file-earmark-arrow-up me-1.5"></i> Import Data
                    </button>
                    <button class="btn btn-outline-secondary" onclick="UsersCore.refreshData()" style="min-height: 38px; font-size: 0.85rem;">
                        <i class="bi bi-arrow-clockwise me-1.5"></i> Refresh
                    </button>
                </div>
            </div>

            {{-- ══════ FILTER SECTION ══════ --}}
            <div class="mb-4">
                <x-user-filter-card />
            </div>

            {{-- ══════ USER STATISTICS (4 KPI Cards from DB) ══════ --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-md-3">
                    <x-user-stat-card title="Total User" value="{{ $users->count() }} Users" icon="people" color="primary" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-user-stat-card title="Active User" value="{{ $users->count() }} Active" icon="check-circle" color="success" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-user-stat-card title="Inactive User" value="0 Inactive" icon="slash-circle" color="secondary" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-user-stat-card title="Administrator" value="{{ $users->filter(fn($u) => (is_object($u->role) ? $u->role->value : $u->role) === 'admin')->count() }} Admins" icon="shield-lock" color="warning" />
                </div>
            </div>

            {{-- ══════ USER TABLE (Real DB Data) ══════ --}}
            <x-user-table>
                @foreach($users as $u)
                    @php
                        $roleStr = is_object($u->role) ? ($u->role->value ?? 'user') : ($u->role ?? 'user');
                    @endphp
                    <x-user-row 
                        :name="$u->name" 
                        :email="$u->email" 
                        :role="ucfirst($roleStr)" 
                        status="Active" 
                        :joined="$u->created_at ? $u->created_at->format('d-m-Y') : '18-07-2026'" 
                        :lastLogin="$u->updated_at ? $u->updated_at->format('d-m-Y H:i') : 'Hari ini'" 
                        :avatar="'https://ui-avatars.com/api/?name=' . urlencode($u->name) . '&background=2563EB&color=fff'"
                        phone="+62 812-3456-7890"
                        company="SupplyChain Platform"
                        country="Indonesia"
                    />
                @endforeach
            </x-user-table>

            {{-- ══════ PAGINATION ══════ --}}
            <div class="mt-4 mb-4">
                <x-user-pagination />
            </div>

        </div>

        {{-- Action Simulator Testing UI --}}
        <div class="d-flex justify-content-end gap-2 pt-3 border-top mb-4">
            <button class="btn btn-sm btn-outline-danger" onclick="UsersCore.simulateError()">
                ⚡ Simulasikan Error Layanan
            </button>
        </div>
    </div>

    {{-- ══════ MODAL DIALOGS ══════ --}}
    <x-user-modal-create />
    <x-user-modal-edit />
    <x-user-modal-detail />
    <x-user-modal-reset-password />
    <x-user-modal-delete />
@endsection

@section('scripts')
    <!-- User Management scripts -->
    <script src="{{ asset('js/admin/users/users.js') }}"></script>
    <script src="{{ asset('js/admin/users/modal.js') }}"></script>
    <script src="{{ asset('js/admin/users/table.js') }}"></script>
    <script src="{{ asset('js/admin/users/filter.js') }}"></script>
    <script src="{{ asset('js/admin/users/search.js') }}"></script>
@endsection
