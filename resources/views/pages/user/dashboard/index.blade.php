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
    #leaflet-map-wrapper { position: relative; width: 100%; height: 500px; min-height: 500px; }
    #leaflet-map { position: absolute !important; top: 0; left: 0; right: 0; bottom: 0; width: 100% !important; height: 100% !important; z-index: 1; background-color: #EAF3FF; }
    .kpi-value { font-size: 1.75rem; font-weight: 700; }
    .risk-bar { height: 6px; border-radius: 3px; background: #E2E8F0; overflow: hidden; }
    .risk-bar-fill { height: 100%; border-radius: 3px; transition: width 0.5s ease; }
    .animate-spin { animation: spin 1s linear infinite; display: inline-block; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
    .weather-card { background: #F8FAFC !important; border: 1px solid #E2E8F0; border-radius: 12px; padding: 12px; }
    .rate-card { background: #F8FAFC !important; border: 1px solid #E2E8F0; border-radius: 10px; padding: 10px 14px; }
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
