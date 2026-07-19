{{-- ═══════════════════════════════════════════════════
     USER DASHBOARD – Milestone 3.16A
     resources/views/pages/user/dashboard/index.blade.php
     Extends layouts.user.app (new architecture)
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.user.app')

@section('title', 'Dashboard Global Supply Chain - SupplyChain Platform')

@section('styles')
    {{-- Module-level styles loaded per-page --}}
@endsection

@section('content')
    {{-- Delegate to original dashboard content --}}
    @include('dashboard.index')
@endsection

@section('scripts')
    {{-- Module-level scripts loaded per-page --}}
@endsection
