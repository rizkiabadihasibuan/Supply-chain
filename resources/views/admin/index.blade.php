{{-- ═══════════════════════════════════════════════════
     ADMIN DASHBOARD OVERVIEW – Milestone 3.15A
     resources/views/admin/index.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Admin Dashboard - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/chart.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/responsive.css') }}">
@endsection

@section('content')
    <div class="admin-dashboard-wrapper">
        {{-- ══════ HEADER ══════ --}}
        <x-admin-header />

        {{-- Container Error Simulator --}}
        <div id="admin-error-container" style="display: none;" class="mb-2">
            <div class="alert alert-danger d-flex align-items-center justify-content-between p-4" role="alert">
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-exclamation-triangle-fill fs-4"></i>
                    <div>
                        <h6 class="alert-heading fw-bold mb-0.5">Gagal terhubung ke modul monitoring satelit admin!</h6>
                        <span class="small">Terjadi hambatan sinkronisasi data real-time API. Silakan coba memulihkan server.</span>
                    </div>
                </div>
                <button class="btn btn-sm btn-danger border-white border" onclick="AdminDashboard.retryFromError()">Coba Lagi</button>
            </div>
        </div>

        {{-- Skeleton Loading Container --}}
        <div id="admin-skeleton-container" class="row g-4">
            <div class="col-12">
                <x-loading-state type="card" count="4" height="120px" />
                <x-loading-state type="card" count="2" height="280px" />
            </div>
        </div>

        {{-- ══════ MAIN DASHBOARD CONTENT (Hidden on loading skeleton) ══════ --}}
        <div id="admin-main-content" style="display: none;">
            
            {{-- ══════ QUICK STATISTICS (8 KPI Cards) ══════ --}}
            <div class="row g-3 kpi-row mb-4">
                <div class="col-admin-kpi">
                    <x-admin-stat-card title="Total Users" icon="people" value="245 Users" subtitle="Registered Users" />
                </div>
                <div class="col-admin-kpi">
                    <x-admin-stat-card title="Countries" icon="globe" value="195" subtitle="Monitored Countries" />
                </div>
                <div class="col-admin-kpi">
                    <x-admin-stat-card title="Ports" icon="anchor" value="1,250" subtitle="World Ports Dataset" />
                </div>
                <div class="col-admin-kpi">
                    <x-admin-stat-card title="Articles" icon="newspaper" value="315" subtitle="Logistic Articles" />
                </div>
                <div class="col-admin-kpi">
                    <x-admin-stat-card title="Watchlists" icon="bookmark-star" value="92" subtitle="Active Watchlists" />
                </div>
                <div class="col-admin-kpi">
                    <x-admin-stat-card title="Risk Records" icon="exclamation-triangle" value="5,240" subtitle="Monitored Risks" />
                </div>
                <div class="col-admin-kpi">
                    <x-admin-stat-card title="API Status" icon="wifi" value="6 / 6" subtitle="External Integration" badgeText="Healthy" badgeColor="success" />
                </div>
                <div class="col-admin-kpi">
                    <x-admin-stat-card title="System Health" icon="cpu" value="99.98%" subtitle="Uptime SLA Score" badgeText="Excellent" badgeColor="success" />
                </div>
            </div>

            {{-- ══════ ANALYTICS SECTION (4 Charts + API Monitoring) ══════ --}}
            <div class="row g-4 mb-4">
                {{-- Left: Charts --}}
                <div class="col-12 col-xl-8">
                    <div class="row g-4">
                        <div class="col-12 col-md-6">
                            <x-admin-chart-card title="User Growth" chartId="userGrowthChart" description="Pertumbuhan kumulatif user terdaftar." />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin-chart-card title="Risk Distribution" chartId="riskDistChart" description="Pembagian tingkat risiko saat ini." />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin-chart-card title="Article Statistics" chartId="articleStatsChart" description="Statistik publikasi berita SupplyChain." />
                        </div>
                        <div class="col-12 col-md-6">
                            <x-admin-chart-card title="Watchlist Activity" chartId="watchlistActChart" description="Tingkat interaksi watchlist mingguan." />
                        </div>
                    </div>
                </div>

                {{-- Right: API Monitoring Panel --}}
                <div class="col-12 col-xl-4">
                    <x-api-status-card />
                </div>
            </div>

            {{-- ══════ SYSTEM SUMMARY (Card Besar) ══════ --}}
            <div class="card p-4 border-0 mb-4">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cpu text-primary me-2"></i>Ringkasan Performa Sistem Utama</h6>
                <div class="system-summary-grid">
                    <div class="system-summary-item">
                        <span class="system-summary-label">Total Active User</span>
                        <span class="system-summary-value">185 Active</span>
                    </div>
                    <div class="system-summary-item">
                        <span class="system-summary-label">Total Today's Login</span>
                        <span class="system-summary-value">42 Logs</span>
                    </div>
                    <div class="system-summary-item">
                        <span class="system-summary-label">Total API Request</span>
                        <span class="system-summary-value">28,450 Req</span>
                    </div>
                    <div class="system-summary-item">
                        <span class="system-summary-label">Average Risk Score</span>
                        <span class="system-summary-value">42.5 / 100</span>
                    </div>
                    <div class="system-summary-item">
                        <span class="system-summary-label">Total Countries Monitored</span>
                        <span class="system-summary-value">195 Countries</span>
                    </div>
                    <div class="system-summary-item">
                        <span class="system-summary-label">Total News Collected</span>
                        <span class="system-summary-value">4,120 News</span>
                    </div>
                </div>
            </div>

            {{-- ══════ RECENT ACTIVITY & QUICK ACTIONS / NOTIFICATION ══════ --}}
            <div class="row g-4 mb-4">
                {{-- Left Column: Recent Activities --}}
                <div class="col-12 col-xl-8">
                    <x-recent-activity-table />
                </div>
                
                {{-- Right Column: Quick Actions + Notification --}}
                <div class="col-12 col-xl-4 d-flex flex-column gap-4">
                    <x-quick-action-card />
                    <x-notification-card />
                </div>
            </div>

        </div>

        {{-- Action Simulator Testing UI --}}
        <div class="d-flex justify-content-end gap-2 pt-3 border-top mb-4">
            <button class="btn btn-sm btn-outline-danger" onclick="AdminDashboard.simulateError()">
                ⚡ Simulasikan Error Sistem
            </button>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Admin scripts -->
    <script src="{{ asset('js/admin/chart.js') }}"></script>
    <script src="{{ asset('js/admin/notification.js') }}"></script>
    <script src="{{ asset('js/admin/activity.js') }}"></script>
    <script src="{{ asset('js/admin/dashboard.js') }}"></script>
@endsection
