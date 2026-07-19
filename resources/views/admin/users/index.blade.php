{{-- ═══════════════════════════════════════════════════
     ADMIN USER MANAGEMENT INDEX – Milestone 3.15B
     resources/views/admin/users/index.blade.php
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

        {{-- Skeleton Loading Container --}}
        <div id="users-skeleton-container" class="row g-4">
            <div class="col-12">
                <x-loading-state type="card" count="4" height="100px" />
                <x-loading-state type="card" count="1" height="400px" />
            </div>
        </div>

        {{-- ══════ MAIN CONTENT AREA (Hidden on loading skeleton) ══════ --}}
        <div id="users-main-content" style="display: none;">
            
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

            {{-- ══════ USER STATISTICS (4 KPI Cards) ══════ --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-md-3">
                    <x-user-stat-card title="Total User" value="12 Users" icon="people" color="primary" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-user-stat-card title="Active User" value="9 Active" icon="check-circle" color="success" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-user-stat-card title="Inactive User" value="2 Inactive" icon="slash-circle" color="secondary" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-user-stat-card title="Administrator" value="3 Admins" icon="shield-lock" color="warning" />
                </div>
            </div>

            {{-- ══════ USER TABLE ══════ --}}
            <x-user-table>
                {{-- Data Row 1 --}}
                <x-user-row 
                    name="John Doe" 
                    email="johndoe@domain.com" 
                    role="Admin" 
                    status="Active" 
                    joined="18-07-2026" 
                    lastLogin="18-07-2026 09:54"
                    avatar="https://images.unsplash.com/photo-1535713875002-d1d0cf377fde?q=80&w=100&auto=format&fit=crop"
                    phone="+62 812-3456-7890"
                    company="Logistics Corp ID"
                    country="Indonesia"
                />

                {{-- Data Row 2 --}}
                <x-user-row 
                    name="Jane Smith" 
                    email="janesmith@domain.com" 
                    role="User" 
                    status="Active" 
                    joined="17-07-2026" 
                    lastLogin="17-07-2026 14:15"
                    avatar="https://images.unsplash.com/photo-1494790108377-be9c29b29330?q=80&w=100&auto=format&fit=crop"
                    phone="+1 555-0199-283"
                    company="Global Shipping US"
                    country="United States"
                />

                {{-- Data Row 3 --}}
                <x-user-row 
                    name="Robert Brown" 
                    email="robert@domain.com" 
                    role="User" 
                    status="Inactive" 
                    joined="16-07-2026" 
                    lastLogin="16-07-2026 18:22"
                    avatar="https://images.unsplash.com/photo-1599566150163-29194dcaad36?q=80&w=100&auto=format&fit=crop"
                    phone="+44 7700-900077"
                    company="Cargo Connect UK"
                    country="United Kingdom"
                />

                {{-- Data Row 4 --}}
                <x-user-row 
                    name="Michael Green" 
                    email="michael@domain.com" 
                    role="User" 
                    status="Suspended" 
                    joined="15-07-2026" 
                    lastLogin="15-07-2026 11:05"
                    avatar="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=100&auto=format&fit=crop"
                    phone="+49 170-123456"
                    company="FastRoute DE"
                    country="Germany"
                />

                {{-- Data Row 5 --}}
                <x-user-row 
                    name="Admin Utama" 
                    email="admin@supplychain.com" 
                    role="Admin" 
                    status="Active" 
                    joined="01-07-2026" 
                    lastLogin="18-07-2026 09:59"
                    avatar="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=100&auto=format&fit=crop"
                    phone="+62 811-999-888"
                    company="SupplyChain HQ"
                    country="Indonesia"
                />
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
