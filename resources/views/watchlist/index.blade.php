{{--
    Favorite Monitoring List – Milestone 3.12
    resources/views/watchlist/index.blade.php

    Layout: Header → Toolbar → Stats → Grid → Table → Footer
--}}
@extends('layouts.user.app')

@section('title', 'Favorite Monitoring List – SupplyChain Platform')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/favorite/favorite.css') }}">
<link rel="stylesheet" href="{{ asset('css/favorite/toolbar.css') }}">
<link rel="stylesheet" href="{{ asset('css/favorite/responsive.css') }}">
@endsection

@section('content')
<div class="fav-page-wrapper">

{{-- ======================================================
     PAGE HEADER
     ====================================================== --}}
<div class="row align-items-center mb-4 g-3">
    {{-- Left: Title --}}
    <div class="col-12 col-md-7">
        <div class="d-flex align-items-center gap-3">
            <div class="p-2 rounded-3" style="background:rgba(37,99,235,0.08);">
                <i class="bi bi-star-fill text-primary fs-4"></i>
            </div>
            <div>
                <h4 class="fw-bold text-dark mb-0">Favorite Monitoring List</h4>
                <p class="text-secondary small mb-0 mt-1">
                    Pantau negara-negara favorit Anda dalam satu dashboard ringkas.
                </p>
            </div>
        </div>
    </div>

    {{-- Right: Actions --}}
    <div class="col-12 col-md-5 d-flex justify-content-md-end align-items-center gap-2 flex-wrap">
        <span class="badge bg-white text-secondary border shadow-sm px-3 py-2 rounded-pill d-flex align-items-center gap-1" style="font-size:.78rem;">
            <i class="bi bi-calendar3 text-primary"></i>
            {{ now()->translatedFormat('d F Y') }}
        </span>

        {{-- Refresh --}}
        <button id="btnHeaderRefresh"
                class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2"
                style="min-height:38px; border-radius:10px;"
                onclick="refreshFavorites(this)"
                data-bs-toggle="tooltip" title="Segarkan semua data">
            <i class="bi bi-arrow-clockwise"></i>
            <span class="d-none d-sm-inline">Segarkan</span>
        </button>

        {{-- Add Favorite --}}
        <button class="btn btn-primary btn-sm d-flex align-items-center gap-2"
                style="min-height:38px; border-radius:10px;"
                onclick="openAddModal()">
            <i class="bi bi-plus-circle-fill"></i>
            <span class="d-none d-sm-inline">Tambah Favorit</span>
        </button>
    </div>
</div>

{{-- ======================================================
     TOOLBAR
     ====================================================== --}}
<div class="fav-toolbar mb-4 fav-fade">
    <div class="row g-2 align-items-center">

        {{-- Search --}}
        <div class="col-12 col-sm-6 col-lg-3">
            <div class="input-group">
                <span class="input-group-text border-end-0"><i class="bi bi-search"></i></span>
                <input type="text" id="favSearch"
                       class="form-control border-start-0"
                       placeholder="Cari negara favorit..."
                       style="border-radius:0 10px 10px 0;"
                       oninput="FavoriteToolbar.apply()">
            </div>
        </div>

        {{-- Filter Region --}}
        <div class="col-6 col-sm-3 col-lg-2">
            <select id="favFilterRegion" class="form-select" onchange="FavoriteToolbar.apply()">
                <option value="all">Semua Wilayah</option>
                <option value="Asia Tenggara">Asia Tenggara</option>
                <option value="Asia Timur">Asia Timur</option>
                <option value="Amerika Utara">Amerika Utara</option>
                <option value="Eropa Barat">Eropa Barat</option>
                <option value="Afrika">Afrika</option>
                <option value="Oceania">Oceania</option>
            </select>
        </div>

        {{-- Filter Risk --}}
        <div class="col-6 col-sm-3 col-lg-2">
            <select id="favFilterRisk" class="form-select" onchange="FavoriteToolbar.apply()">
                <option value="all">Semua Risk</option>
                <option value="low">Low Risk</option>
                <option value="medium">Medium Risk</option>
                <option value="high">High Risk</option>
            </select>
        </div>

        {{-- Sort --}}
        <div class="col-6 col-sm-3 col-lg-2">
            <select id="favSort" class="form-select" onchange="FavoriteToolbar.apply()">
                <option value="name">Urutkan: Nama</option>
                <option value="risk-asc">Risk ↑ Terendah</option>
                <option value="risk-desc">Risk ↓ Tertinggi</option>
                <option value="gdp-desc">GDP ↓ Terbesar</option>
            </select>
        </div>

        {{-- Actions --}}
        <div class="col-6 col-sm-9 col-lg-auto ms-lg-auto d-flex gap-2 flex-wrap">
            <button id="btnResetFilters" class="fav-toolbar-btn btn btn-outline-secondary"
                    data-bs-toggle="tooltip" title="Reset filter">
                <i class="bi bi-x-circle"></i>
                <span class="d-none d-md-inline">Reset</span>
            </button>
            <button class="fav-toolbar-btn btn btn-primary" onclick="openAddModal()">
                <i class="bi bi-plus-lg"></i>
                <span>Tambah</span>
            </button>
        </div>

    </div>
</div>

{{-- ======================================================
     SKELETON LOADING
     ====================================================== --}}
<div id="favSkeleton" style="display:none;">

    {{-- Stat cards skeleton --}}
    <div class="row g-3 mb-4">
        @for($i = 0; $i < 4; $i++)
        <div class="col-6 col-md-3">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="placeholder" style="width:48px;height:48px;border-radius:12px;background:#E2E8F0;"></span>
                    <span class="placeholder col-3 rounded-pill" style="height:22px;"></span>
                </div>
                <span class="placeholder col-6 d-block mb-2" style="height:12px;border-radius:6px;"></span>
                <span class="placeholder col-8 d-block" style="height:26px;border-radius:8px;"></span>
            </div>
        </div>
        @endfor
    </div>

    {{-- Grid skeleton --}}
    <div class="row g-3">
        @for($i = 0; $i < 4; $i++)
        <div class="col-12 col-md-6 col-lg-3">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="placeholder" style="width:56px;height:56px;border-radius:50%;background:#E2E8F0;"></span>
                    <div class="flex-grow-1">
                        <span class="placeholder col-7 d-block mb-2" style="height:14px;border-radius:6px;"></span>
                        <span class="placeholder col-5 d-block" style="height:10px;border-radius:6px;"></span>
                    </div>
                </div>
                @for($r = 0; $r < 5; $r++)
                <div class="d-flex justify-content-between mb-2">
                    <span class="placeholder col-4" style="height:11px;border-radius:5px;"></span>
                    <span class="placeholder col-3" style="height:11px;border-radius:5px;"></span>
                </div>
                @endfor
                <div class="d-flex gap-2 mt-3">
                    <span class="placeholder col-4" style="height:32px;border-radius:8px;"></span>
                    <span class="placeholder col-4" style="height:32px;border-radius:8px;"></span>
                    <span class="placeholder col-4" style="height:32px;border-radius:8px;"></span>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

{{-- ======================================================
     ERROR STATE
     ====================================================== --}}
<div id="favError" class="py-5 text-center fav-fade" style="display:none;">
    <div class="d-flex justify-content-center mb-4">
        <div class="p-4 rounded-circle" style="background:rgba(220,38,38,0.08);">
            <i class="bi bi-exclamation-triangle-fill text-danger" style="font-size:3rem;"></i>
        </div>
    </div>
    <h5 class="fw-bold text-dark mb-2">Data gagal dimuat.</h5>
    <p class="text-secondary mb-4" style="max-width:340px;margin:0 auto;">Koneksi bermasalah. Silakan coba kembali beberapa saat.</p>
    <button id="btnRetry" class="btn btn-danger px-4" style="border-radius:10px;min-height:40px;">
        <i class="bi bi-arrow-clockwise me-2"></i>Coba Lagi
    </button>
</div>

{{-- ======================================================
     MAIN CONTENT
     ====================================================== --}}
<div id="favContent" style="display:none;">

    {{-- ------------------------------------------------
         STATISTICS CARDS
         ------------------------------------------------ --}}
    <div class="row g-3 mb-4">

        {{-- Total Favorites --}}
        <div class="col-6 col-md-3 fav-fade fav-s1">
            <div class="card fav-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="fav-stat-icon" style="background:rgba(37,99,235,.09);">
                        <i class="bi bi-star-fill text-primary"></i>
                    </div>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill" style="font-size:.7rem;">Total</span>
                </div>
                <p class="text-secondary small fw-medium mb-1">Negara Favorit</p>
                <div class="fav-stat-value text-dark" id="statTotal">–</div>
            </div>
        </div>

        {{-- Average Risk --}}
        <div class="col-6 col-md-3 fav-fade fav-s2">
            <div class="card fav-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="fav-stat-icon" style="background:rgba(217,119,6,.09);">
                        <i class="bi bi-shield-half text-warning"></i>
                    </div>
                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill" style="font-size:.7rem;">Rata-rata</span>
                </div>
                <p class="text-secondary small fw-medium mb-1">Average Risk Score</p>
                <div class="fav-stat-value text-warning" id="statAvgRisk">–</div>
            </div>
        </div>

        {{-- Highest Risk --}}
        <div class="col-6 col-md-3 fav-fade fav-s3">
            <div class="card fav-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="fav-stat-icon" style="background:rgba(220,38,38,.09);">
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    </div>
                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 rounded-pill" style="font-size:.7rem;">Tertinggi</span>
                </div>
                <p class="text-secondary small fw-medium mb-1">Highest Risk</p>
                <div class="fav-stat-value text-danger" id="statMaxRisk">–</div>
            </div>
        </div>

        {{-- Safe Countries --}}
        <div class="col-6 col-md-3 fav-fade fav-s4">
            <div class="card fav-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div class="fav-stat-icon" style="background:rgba(22,163,74,.09);">
                        <i class="bi bi-shield-check text-success"></i>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 rounded-pill" style="font-size:.7rem;">Aman</span>
                </div>
                <p class="text-secondary small fw-medium mb-1">Safe Countries</p>
                <div class="fav-stat-value text-success" id="statSafe">–</div>
            </div>
        </div>

    </div>{{-- /stats --}}

    {{-- ------------------------------------------------
         EMPTY STATE (inside content, shown when filtered empty)
         ------------------------------------------------ --}}
    <div id="favEmpty" class="py-5 text-center fav-fade" style="display:none;">
        <div class="d-flex justify-content-center mb-4">
            <svg width="180" height="140" viewBox="0 0 180 140" fill="none" xmlns="http://www.w3.org/2000/svg">
                <ellipse cx="90" cy="128" rx="70" ry="8" fill="#F1F5F9"/>
                <rect x="30" y="50" width="120" height="75" rx="12" fill="#EFF6FF" stroke="#BFDBFE" stroke-width="1.5"/>
                <rect x="45" y="64" width="90" height="8" rx="4" fill="#BFDBFE"/>
                <rect x="45" y="78" width="65" height="6" rx="3" fill="#DBEAFE"/>
                <rect x="45" y="90" width="78" height="6" rx="3" fill="#DBEAFE"/>
                <circle cx="90" cy="36" r="20" fill="#FEF3C7" stroke="#FDE68A" stroke-width="1.5"/>
                <text x="90" y="42" text-anchor="middle" font-size="18" fill="#F59E0B">★</text>
            </svg>
        </div>
        <h5 class="fw-bold text-dark mb-2">Belum ada negara favorit.</h5>
        <p class="text-secondary mb-4" style="max-width:360px;margin:0 auto;">
            Klik tombol <strong>Tambah Favorit</strong> untuk mulai memantau negara yang ingin Anda ikuti.
        </p>
        <div class="d-flex justify-content-center gap-3 flex-wrap">
            <button class="btn btn-primary px-4" style="border-radius:10px;min-height:40px;" onclick="openAddModal()">
                <i class="bi bi-plus-circle-fill me-2"></i>Tambah Favorit
            </button>
            <button class="btn btn-outline-secondary px-4" style="border-radius:10px;min-height:40px;" onclick="FavoriteToolbar.reset()">
                <i class="bi bi-x-circle me-2"></i>Reset Filter
            </button>
        </div>
    </div>

    {{-- ------------------------------------------------
         FAVORITE GRID
         ------------------------------------------------ --}}
    <div id="favGrid" class="row g-3 mb-4">

        @php
        $favCountries = [
            ['code'=>'ID','flag'=>'🇮🇩','name'=>'Indonesia','region'=>'Asia Tenggara','gdp'=>'$1.42T','gdpRaw'=>1.42,'inflation'=>'2.8%','inflationRaw'=>2.8,'currency'=>'Rp 16.250','weather'=>'32°C ☀️','riskScore'=>1.25,'riskLevel'=>'low','population'=>'279.1 Juta','lastUpdate'=>'Baru saja'],
            ['code'=>'US','flag'=>'🇺🇸','name'=>'Amerika Serikat','region'=>'Amerika Utara','gdp'=>'$28.20T','gdpRaw'=>28.20,'inflation'=>'3.4%','inflationRaw'=>3.4,'currency'=>'USD Base','weather'=>'22°C 🌥','riskScore'=>3.48,'riskLevel'=>'medium','population'=>'335.9 Juta','lastUpdate'=>'1 Jam lalu'],
            ['code'=>'SG','flag'=>'🇸🇬','name'=>'Singapura','region'=>'Asia Tenggara','gdp'=>'$0.50T','gdpRaw'=>0.50,'inflation'=>'2.4%','inflationRaw'=>2.4,'currency'=>'S$ 1.34','weather'=>'30°C 🌫','riskScore'=>1.10,'riskLevel'=>'low','population'=>'5.9 Juta','lastUpdate'=>'30 Mnt lalu'],
            ['code'=>'CN','flag'=>'🇨🇳','name'=>'China','region'=>'Asia Timur','gdp'=>'$17.90T','gdpRaw'=>17.90,'inflation'=>'1.8%','inflationRaw'=>1.8,'currency'=>'¥ 7.24','weather'=>'28°C 🌤','riskScore'=>4.25,'riskLevel'=>'high','population'=>'1.41 Miliar','lastUpdate'=>'5 Mnt lalu'],
            ['code'=>'DE','flag'=>'🇩🇪','name'=>'Jerman','region'=>'Eropa Barat','gdp'=>'$4.08T','gdpRaw'=>4.08,'inflation'=>'2.9%','inflationRaw'=>2.9,'currency'=>'€ 0.92','weather'=>'16°C 🌥','riskScore'=>1.95,'riskLevel'=>'low','population'=>'84.3 Juta','lastUpdate'=>'2 Jam lalu'],
        ];
        @endphp

        @foreach($favCountries as $i => $c)
        @php
            $rClass = $c['riskLevel'];
            $rBadgeCls = match($c['riskLevel']) {
                'high'   => 'high',
                'medium' => 'medium',
                default  => 'low',
            };
            $rBadgeLabel = match($c['riskLevel']) {
                'high'   => 'High Risk',
                'medium' => 'Medium Risk',
                default  => 'Low Risk',
            };
            $rBarCls = match($c['riskLevel']) {
                'high'   => 'bg-danger',
                'medium' => 'bg-warning',
                default  => 'bg-success',
            };
        @endphp

        <div class="col-12 col-md-6 col-xl-3 fav-grid-col fav-fade" style="animation-delay: {{ $i * 0.07 }}s;">
            <div class="fav-country-card risk-{{ $rClass }} p-4 h-100 d-flex flex-column"
                 data-fav-card
                 data-code="{{ $c['code'] }}"
                 data-name="{{ strtolower($c['name']) }}"
                 data-region="{{ $c['region'] }}"
                 data-risk="{{ $c['riskLevel'] }}"
                 data-gdp="{{ $c['gdpRaw'] }}">

                {{-- Remove button --}}
                <button class="fav-remove-btn"
                        onclick="removeFavorite('{{ $c['code'] }}', this)"
                        data-bs-toggle="tooltip" title="Hapus dari Favorite">
                    <i class="bi bi-x-lg"></i>
                </button>

                {{-- Header --}}
                <div class="d-flex align-items-center gap-3 mb-3">
                    <span class="fav-country-flag d-flex align-items-center justify-content-center rounded-circle border shadow-sm"
                          style="width:56px;height:56px;background:#F8FAFC;font-size:1.7rem;flex-shrink:0;">
                        {{ $c['flag'] }}
                    </span>
                    <div>
                        <h6 class="fw-bold text-dark mb-0">{{ $c['name'] }}</h6>
                        <small class="text-secondary">{{ $c['region'] }}</small>
                    </div>
                </div>

                {{-- Risk badge + bar --}}
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="risk-badge {{ $rBadgeCls }}">{{ $rBadgeLabel }}</span>
                        <span class="text-dark fw-bold small">{{ $c['riskScore'] }}/5</span>
                    </div>
                    <div class="progress fav-risk-bar">
                        <div class="progress-bar {{ $rBarCls }}" style="width:{{ ($c['riskScore'] / 5) * 100 }}%;"></div>
                    </div>
                </div>

                {{-- Info rows --}}
                <div class="flex-grow-1 mb-3">
                    <div class="fav-info-row">
                        <span class="text-secondary d-flex align-items-center gap-1"><i class="bi bi-cash-coin text-primary"></i> GDP</span>
                        <span class="fw-semibold text-dark">{{ $c['gdp'] }}</span>
                    </div>
                    <div class="fav-info-row">
                        <span class="text-secondary d-flex align-items-center gap-1"><i class="bi bi-percent text-warning"></i> Inflasi</span>
                        <span class="fw-semibold {{ $c['inflationRaw'] > 10 ? 'text-danger' : ($c['inflationRaw'] > 4 ? 'text-warning' : 'text-success') }}">{{ $c['inflation'] }}</span>
                    </div>
                    <div class="fav-info-row">
                        <span class="text-secondary d-flex align-items-center gap-1"><i class="bi bi-currency-exchange text-info"></i> Kurs</span>
                        <span class="text-dark">{{ $c['currency'] }}</span>
                    </div>
                    <div class="fav-info-row">
                        <span class="text-secondary d-flex align-items-center gap-1"><i class="bi bi-thermometer-half text-danger"></i> Cuaca</span>
                        <span class="text-dark">{{ $c['weather'] }}</span>
                    </div>
                    <div class="fav-info-row">
                        <span class="text-secondary d-flex align-items-center gap-1"><i class="bi bi-clock text-secondary"></i> Update</span>
                        <span class="text-secondary small">{{ $c['lastUpdate'] }}</span>
                    </div>
                </div>

                {{-- Quick actions --}}
                <div class="fav-card-actions">
                    <a href="{{ route('countries.detail') }}"
                       class="btn btn-light border"
                       data-bs-toggle="tooltip" title="Lihat Detail">
                        <i class="bi bi-eye"></i>
                    </a>
                    <a href="{{ route('comparison') }}"
                       class="btn btn-outline-primary"
                       data-bs-toggle="tooltip" title="Bandingkan">
                        <i class="bi bi-columns-gap"></i>
                    </a>
                    <button class="btn btn-outline-danger"
                            onclick="removeFavorite('{{ $c['code'] }}', this)"
                            data-bs-toggle="tooltip" title="Hapus Favorit">
                        <i class="bi bi-trash3"></i>
                    </button>
                </div>

            </div>
        </div>
        @endforeach

    </div>{{-- /favGrid --}}

    {{-- ------------------------------------------------
         FAVORITE TABLE
         ------------------------------------------------ --}}
    <div class="card fav-card overflow-hidden mb-4 fav-fade">

        {{-- Table header --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 p-4"
             style="background:#FAFCFF; border-bottom:1px solid #E2E8F0;">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-table text-primary fs-5"></i>
                <h6 class="fw-bold text-dark mb-0">Tabel Ringkasan Favorit</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-pill px-2" style="font-size:.72rem;">
                    <span id="favCount">5</span> negara
                </span>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <div class="dropdown">
                    <button class="btn btn-light btn-sm dropdown-toggle d-flex align-items-center gap-1" type="button"
                            data-bs-toggle="dropdown" style="height:34px;border-radius:8px;font-size:.82rem;">
                        <i class="bi bi-download"></i> Ekspor
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                        <li><button class="dropdown-item small py-2" onclick="alert('Simulasi ekspor PDF...')"><i class="bi bi-filetype-pdf text-danger me-2"></i>PDF</button></li>
                        <li><button class="dropdown-item small py-2" onclick="alert('Simulasi ekspor Excel...')"><i class="bi bi-filetype-xls text-success me-2"></i>Excel</button></li>
                        <li><button class="dropdown-item small py-2" onclick="alert('Simulasi ekspor CSV...')"><i class="bi bi-filetype-csv text-info me-2"></i>CSV</button></li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Table --}}
        <div class="table-responsive">
            <table class="table table-hover table-striped mb-0" style="font-size:.855rem;">
                <thead style="background:#F8FAFC;">
                    <tr>
                        <th class="py-3 px-4" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">Negara</th>
                        <th class="py-3" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">Wilayah</th>
                        <th class="py-3" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">GDP</th>
                        <th class="py-3" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">Inflasi</th>
                        <th class="py-3" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">Kurs</th>
                        <th class="py-3" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">Cuaca</th>
                        <th class="py-3" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">Risk</th>
                        <th class="py-3" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">Update</th>
                        <th class="py-3 pe-4" style="font-size:.73rem;font-weight:600;text-transform:uppercase;letter-spacing:.5px;color:#64748B;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($favCountries as $c)
                    @php
                        $inflCls = $c['inflationRaw'] > 10 ? 'text-danger fw-bold' : ($c['inflationRaw'] > 4 ? 'text-warning fw-semibold' : 'text-success');
                        $rBdgCls = match($c['riskLevel']) { 'high' => 'high', 'medium' => 'medium', default => 'low' };
                    @endphp
                    <tr data-fav-row
                        data-code="{{ $c['code'] }}"
                        data-name="{{ strtolower($c['name']) }}"
                        data-region="{{ $c['region'] }}"
                        data-risk="{{ $c['riskLevel'] }}">
                        <td class="px-4 py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span style="font-size:1.15rem;">{{ $c['flag'] }}</span>
                                <span class="fw-semibold text-dark">{{ $c['name'] }}</span>
                            </div>
                        </td>
                        <td class="py-3 text-secondary">{{ $c['region'] }}</td>
                        <td class="py-3 fw-semibold text-dark">{{ $c['gdp'] }}</td>
                        <td class="py-3 {{ $inflCls }}">{{ $c['inflation'] }}</td>
                        <td class="py-3 text-secondary">{{ $c['currency'] }}</td>
                        <td class="py-3 text-dark">{{ $c['weather'] }}</td>
                        <td class="py-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="risk-badge {{ $rBdgCls }}">
                                    {{ match($c['riskLevel']) { 'high'=>'High','medium'=>'Medium',default=>'Low' } }}
                                </span>
                                <small class="fw-bold {{ match($c['riskLevel']) { 'high'=>'text-danger','medium'=>'text-warning',default=>'text-success' } }}">
                                    {{ $c['riskScore'] }}
                                </small>
                            </div>
                        </td>
                        <td class="py-3 text-secondary" style="font-size:.8rem;">{{ $c['lastUpdate'] }}</td>
                        <td class="py-3 pe-4">
                            <div class="d-flex gap-1">
                                <a href="{{ route('countries.detail') }}"
                                   class="btn btn-light btn-sm border"
                                   style="height:30px;width:30px;padding:0;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;"
                                   data-bs-toggle="tooltip" title="Lihat Detail">
                                    <i class="bi bi-eye" style="font-size:.78rem;"></i>
                                </a>
                                <a href="{{ route('comparison') }}"
                                   class="btn btn-outline-primary btn-sm"
                                   style="height:30px;width:30px;padding:0;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;"
                                   data-bs-toggle="tooltip" title="Bandingkan">
                                    <i class="bi bi-columns-gap" style="font-size:.78rem;"></i>
                                </a>
                                <button onclick="removeFavorite('{{ $c['code'] }}', this)"
                                        class="btn btn-outline-danger btn-sm"
                                        style="height:30px;width:30px;padding:0;border-radius:7px;display:inline-flex;align-items:center;justify-content:center;"
                                        data-bs-toggle="tooltip" title="Hapus">
                                    <i class="bi bi-trash3" style="font-size:.78rem;"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Table footer: pagination --}}
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 px-4 py-3"
             style="background:#FAFCFF;border-top:1px solid #E2E8F0;border-radius:0 0 16px 16px;">
            <p class="text-secondary small mb-0">
                Menampilkan <span id="favCount" class="fw-semibold text-dark">5</span> negara favorit
            </p>
            <nav>
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link rounded-start-3" href="#">«</a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item disabled"><a class="page-link rounded-end-3" href="#">»</a></li>
                </ul>
            </nav>
        </div>

    </div>{{-- /table card --}}

</div>{{-- /favContent --}}

{{-- ======================================================
     ADD FAVORITE MODAL
     ====================================================== --}}
<div class="modal fade" id="addFavoriteModal" tabindex="-1" aria-labelledby="addFavoriteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width:460px;">
        <div class="modal-content border-0 shadow-lg rounded-4">

            <div class="modal-header border-0 pb-0 px-4 pt-4">
                <div class="d-flex align-items-center gap-2">
                    <div class="p-2 rounded-3" style="background:rgba(37,99,235,0.08);">
                        <i class="bi bi-star-fill text-primary"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-dark" id="addFavoriteModalLabel">Tambah Negara Favorit</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body px-4 pt-3">
                <p class="text-secondary small mb-4">Cari dan pilih negara yang ingin Anda tambahkan ke daftar pemantauan favorit.</p>

                {{-- Search --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Cari Negara</label>
                    <div class="input-group">
                        <span class="input-group-text" style="background:#F8FAFC;border-radius:10px 0 0 10px;">
                            <i class="bi bi-search text-secondary"></i>
                        </span>
                        <input type="text" id="modalCountrySearch"
                               class="form-control" placeholder="Ketik nama negara..."
                               style="border-radius:0 10px 10px 0;">
                    </div>
                </div>

                {{-- Dropdown --}}
                <div class="mb-3">
                    <label class="form-label fw-semibold small text-dark">Pilih Negara</label>
                    <select id="modalCountrySelect" class="form-select" style="border-radius:10px;">
                        <option value="">-- Pilih Negara --</option>
                    </select>
                    <div id="modalValidation" class="text-danger small mt-1" style="display:none;">
                        <i class="bi bi-exclamation-circle me-1"></i>Silakan pilih negara terlebih dahulu.
                    </div>
                </div>

                <div class="alert alert-info border-0 rounded-3 py-2 px-3" style="background:#EFF6FF;font-size:.82rem;">
                    <i class="bi bi-info-circle me-1 text-primary"></i>
                    Data akan diambil dari <strong>REST Countries API + World Bank API</strong> setelah backend siap.
                </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 gap-2">
                <button type="button"
                        class="btn btn-outline-secondary px-4"
                        style="border-radius:10px;"
                        data-bs-dismiss="modal">
                    Batal
                </button>
                <button type="button" id="btnModalSave"
                        class="btn btn-primary px-4"
                        style="border-radius:10px;">
                    <i class="bi bi-star-fill me-2"></i>Simpan ke Favorit
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ======================================================
     TOAST NOTIFICATION
     ====================================================== --}}
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index:9999;">
    <div id="favToast" class="toast border-0 shadow-sm rounded-3" role="alert">
        <div class="toast-header border-0" style="background:#2563EB;">
            <i class="bi bi-star-fill text-white me-2"></i>
            <strong class="text-white me-auto">Favorite</strong>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body small" id="favToastMsg">Aksi berhasil.</div>
    </div>
</div>

</div>{{-- /fav-page-wrapper --}}
@endsection

@section('scripts')
<script>
{!! file_get_contents(resource_path('js/favorite/favorite-data.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/favorite/favorite-toolbar.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/favorite/favorite-modal.js')) !!}
</script>
<script>
{!! file_get_contents(resource_path('js/favorite/favorite.js')) !!}
</script>
@endsection
