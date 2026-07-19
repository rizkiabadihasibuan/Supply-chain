{{-- ═══════════════════════════════════════════════════
     USER FAVORITE / WATCHLIST PAGE – Milestone 3.16A
     resources/views/pages/user/favorite/index.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.user.app')

@section('title', 'Favorite & Watchlist - SupplyChain Platform')

@section('content')
    @include('watchlist.index')
@endsection
