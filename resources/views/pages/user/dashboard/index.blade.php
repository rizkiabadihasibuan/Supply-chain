{{-- ═══════════════════════════════════════════════════
     USER DASHBOARD – Milestone 3.16A
     resources/views/pages/user/dashboard/index.blade.php
     Extends layouts.user.app (new architecture)
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.user.app')

@section('title', 'Dashboard Global Supply Chain - SupplyChain Platform')

@section('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
<style>
    .hero-banner {
        background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #2563eb 100%);
        border-radius: 20px;
        color: #ffffff;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 32px rgba(37, 99, 235, 0.18);
    }
    .hero-banner::before {
        content: '';
        position: absolute;
        top: -40%;
        right: -10%;
        width: 360px;
        height: 360px;
        background: radial-gradient(circle, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        pointer-events: none;
    }
    .kpi-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(15, 23, 42, 0.03);
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(37, 99, 235, 0.12) !important;
        border-color: rgba(37, 99, 235, 0.3) !important;
    }
    .kpi-icon-wrapper {
        width: 52px;
        height: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .kpi-value {
        font-size: 1.85rem;
        font-weight: 800;
        letter-spacing: -0.5px;
    }
    .glass-pill {
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(255, 255, 255, 0.25);
        color: #ffffff;
        border-radius: 30px;
        padding: 4px 14px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    #leaflet-map-wrapper { position: relative; width: 100%; height: 500px; min-height: 500px; }
    #leaflet-map { position: absolute !important; top: 0; left: 0; right: 0; bottom: 0; width: 100% !important; height: 100% !important; z-index: 1; background-color: #EAF3FF; }
    .risk-bar { height: 7px; border-radius: 4px; background: #e2e8f0; overflow: hidden; }
    .risk-bar-fill { height: 100%; border-radius: 4px; transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
    .animate-spin { animation: spin 1s linear infinite; display: inline-block; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .weather-card { background: #f8fafc !important; border: 1px solid #e2e8f0; border-radius: 14px; padding: 14px; transition: all 0.2s; }
    .weather-card:hover { background: #ffffff !important; border-color: #cbd5e1; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    .rate-card { background: #f8fafc !important; border: 1px solid #e2e8f0; border-radius: 12px; padding: 12px 14px; transition: all 0.2s; }
    .rate-card:hover { background: #ffffff !important; border-color: #3b82f6; transform: translateX(2px); }
    .timeline-line { position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background: #E2E8F0; }
    .timeline-dot { position: absolute; left: -19px; top: 5px; width: 10px; height: 10px; border-radius: 50%; border: 2px solid #fff; }
</style>
@endsection

@section('content')
    {{-- Delegate to original dashboard content (pure partial, no @extends/@section inside) --}}
    @include('dashboard.index')
@endsection

@section('scripts')
    {{-- Scripts are inlined inside dashboard.index partial directly --}}
@endsection
