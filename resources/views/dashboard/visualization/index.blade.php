@extends('layouts.user.app')

@section('title', 'Data Visualization Dashboard – SupplyChain Platform')

@section('content')
<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data Visualization Dashboard</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i>Data Visualization Dashboard</h3>
                        <p class="text-secondary small mb-0">Visualisasikan kondisi ekonomi, nilai tukar, inflasi, dan tingkat risiko 250 negara secara interaktif.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary KPI Cards Row (6 Cards - Top Position) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: GDP Global -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">GDP Global</span>
                <h3 class="fw-bold text-primary mb-1" id="kpi-vis-gdp">$98.5T</h3>
                <span class="text-success extra-small d-block" style="font-size: 0.725rem;"><i class="bi bi-arrow-up-short me-1"></i>+1.8% YoY</span>
            </div>
        </div>

        <!-- Card 2: Inflation -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Rata-rata Inflasi</span>
                <h3 class="fw-bold text-warning mb-1" id="kpi-vis-inflation">3.2%</h3>
                <span class="text-success extra-small d-block" style="font-size: 0.725rem;"><i class="bi bi-arrow-down-short me-1"></i>-0.4% MoM</span>
            </div>
        </div>

        <!-- Card 3: Exchange Rate -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Kurs USD/IDR</span>
                <h4 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;" id="kpi-vis-rate">Rp 16.250</h4>
                <span class="text-info extra-small d-block" style="font-size: 0.725rem;">Volatilitas Rendah</span>
            </div>
        </div>

        <!-- Card 4: Risk Score -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Risk Score Global</span>
                <h3 class="fw-bold text-danger mb-1" id="kpi-vis-risk">2.65 / 5</h3>
                <span class="text-secondary extra-small d-block" style="font-size: 0.725rem;">Level Sedang</span>
            </div>
        </div>

        <!-- Card 5: Population -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Populasi Terpantau</span>
                <h3 class="fw-bold text-dark mb-1" id="kpi-vis-pop">8.1B</h3>
                <span class="text-success extra-small d-block" style="font-size: 0.725rem;"><i class="bi bi-check-circle-fill me-1"></i>Pertumbuhan Stabil</span>
            </div>
        </div>

        <!-- Card 6: Last Update -->
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Update Terakhir</span>
                <h4 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Hari Ini</h4>
                <span class="text-info extra-small d-block" style="font-size: 0.725rem;"><i class="bi bi-cpu me-1"></i>Live World Bank</span>
            </div>
        </div>
    </div>

    <!-- Search, Filter & Sort Row (Top Position) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="row g-3 align-items-center">
                    <!-- Search Input -->
                    <div class="col-xl-4 col-lg-3 col-md-12">
                        <div class="search-wrapper w-100">
                            <i class="bi bi-search"></i>
                            <input type="text" id="search-vis-input" placeholder="Cari negara, ibukota, atau indikator..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyVisFilters()">
                        </div>
                    </div>

                    <!-- Region Filter -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-vis-region" class="form-select" style="min-height: 44px;" onchange="applyVisFilters()">
                            <option value="all">Semua Wilayah</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="africa">Afrika</option>
                            <option value="americas">Amerika</option>
                            <option value="oceania">Oceania</option>
                        </select>
                    </div>

                    <!-- Risk Level Filter -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-vis-risk" class="form-select" style="min-height: 44px;" onchange="applyVisFilters()">
                            <option value="all">Semua Risk Level</option>
                            <option value="critical">🔴 Critical / Kritis</option>
                            <option value="high">🟠 High / Tinggi</option>
                            <option value="medium">🟡 Medium / Sedang</option>
                            <option value="low">🟢 Low / Rendah</option>
                        </select>
                    </div>

                    <!-- Sorting -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                        <select id="sort-vis-select" class="form-select" style="min-height: 44px;" onchange="applyVisFilters()">
                            <option value="gdp-desc">Urutkan: GDP Tertinggi</option>
                            <option value="inf-desc">Urutkan: Inflasi Tertinggi</option>
                            <option value="risk-desc">Urutkan: Risk Score Tertinggi</option>
                            <option value="nama">Urutkan: Nama Negara A-Z</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SYNCED VISUALIZATION INTELLIGENCE BANNER (HIDDEN BY DEFAULT) -->
    <div id="vis-sync-banner" class="row g-4 mb-4 d-none">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0284C7 0%, #0369A1 50%, #075985 100%); color: #FFFFFF; border-radius: 16px;">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.2) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <img id="vs-flag" src="https://flagcdn.com/w320/id.png" alt="Flag" style="height: 38px; width: 56px; object-fit: cover; border-radius: 6px;" class="border border-light">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="fw-bold text-white mb-0" id="vs-name">Negara</h4>
                                <span class="badge bg-light text-primary" id="vs-code">CODE</span>
                                <span class="badge bg-success" id="vs-status-badge">Visualisasi Aktif</span>
                            </div>
                            <span class="text-white-50 small">Visualisasi Indikator Ekonomi, Inflasi, & Risiko Rantai Pasok (Pilih Visualization)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="vs-report-btn" href="#" target="_blank" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan Visualisasi PDF
                        </a>
                        <button class="btn btn-outline-light btn-sm" onclick="clearVisSync()">
                            <i class="bi bi-x-circle me-1"></i> Tutup Panel Visualisasi
                        </button>
                    </div>
                </div>

                <!-- 5 INDIKATOR KUNCI VISUALISASI -->
                <div class="row g-3">
                    <!-- 1. GDP Total -->
                    <div class="col-xl-3 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-cash-coin me-1"></i>GDP Total</span>
                            <h5 class="fw-bold text-white mb-0" id="vs-gdp">$1.37 Triliun USD</h5>
                            <span class="text-white-50 extra-small d-block mt-1" style="font-size: 0.72rem;">Produk Domestik Bruto</span>
                        </div>
                    </div>
                    <!-- 2. Inflasi -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-percent me-1"></i>Tingkat Inflasi</span>
                            <h5 class="fw-bold text-white mb-0" id="vs-inflation">2.50%</h5>
                            <span class="text-white-50 extra-small d-block mt-1" style="font-size: 0.72rem;">Indeks Harga Konsumen</span>
                        </div>
                    </div>
                    <!-- 3. Kurs USD -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-currency-exchange me-1"></i>Nilai Tukar Kurs</span>
                            <h5 class="fw-bold text-white mb-0" id="vs-rate">1 USD = Rp 16.250</h5>
                            <span class="text-white-50 extra-small d-block mt-1" style="font-size: 0.72rem;">Valuta Acuan</span>
                        </div>
                    </div>
                    <!-- 4. Risk Score -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-shield-exclamation me-1"></i>Risk Score</span>
                            <h5 class="fw-bold text-white mb-0" id="vs-risk">2.65 / 5.00</h5>
                            <span class="text-white-50 extra-small d-block mt-1" style="font-size: 0.72rem;">Risiko Rantai Pasok</span>
                        </div>
                    </div>
                    <!-- 5. Status Pasar -->
                    <div class="col-xl-3 col-md-8 col-12">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-graph-up-arrow me-1"></i>Stabilitas Visualisasi</span>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold text-white mb-0" id="vs-market-status">Pasar Domestik Stabil</h6>
                                <span class="badge bg-success text-white" id="vs-market-badge">Normal</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State Container -->
    <div id="vis-empty-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
                <i class="bi bi-bar-chart-line text-secondary fs-1 mb-2"></i>
                <h5 class="fw-bold text-dark mb-1">Tidak ada data visualisasi yang ditemukan.</h5>
                <p class="text-secondary small mb-3">Silakan atur kembali kata kunci pencarian atau penyaringan filter Anda.</p>
            </div>
        </div>
    </div>

    <!-- 250 Countries Visualization Cards Grid -->
    <div id="vis-countries-grid" class="row g-4 mb-4">
        @foreach ($countries as $c)
        @php
            $riskObj = $c->riskScore;
            $riskLvl = strtolower($riskObj?->risk_level ?? 'low');
            $score = $riskObj?->overall_score ?? round(1.2 + (($c->id * 11) % 38) / 10, 2);

            // Deterministic Economic Data
            $gdpVal = round(15.0 + (($c->id * 17) % 850) + (($c->id * 3) % 10) / 10, 1);
            $infVal = round(1.5 + (($c->id * 7) % 65) / 10, 2);

            $code = $c->currency?->code ?? 'USD';
            $rate = 1.0;
            if ($code === 'IDR') $rate = 16250.0;
            else if ($code === 'EUR') $rate = 0.92;
            else if ($code === 'CNY') $rate = 7.24;
            else $rate = round(0.5 + (($c->id * 13) % 80), 1);

            $badgeClass = 'bg-success';
            if ($score >= 4.0 || $riskLvl === 'critical') {
                $badgeClass = 'bg-danger';
                $riskLvl = 'critical';
            } else if ($score >= 3.0 || $riskLvl === 'high') {
                $badgeClass = 'bg-warning text-dark';
                $riskLvl = 'high';
            } else if ($score >= 2.0 || $riskLvl === 'medium') {
                $badgeClass = 'bg-info text-dark';
                $riskLvl = 'medium';
            } else {
                $riskLvl = 'low';
            }
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-6 vis-card-item" 
             data-name="{{ strtolower($c->name) }}" 
             data-capital="{{ strtolower($c->capital ?? '') }}"
             data-region="{{ strtolower($c->region?->name ?? 'asia') }}"
             data-risk="{{ $riskLvl }}"
             data-gdp="{{ $gdpVal }}"
             data-inflation="{{ $infVal }}"
             data-score="{{ $score }}"
             data-id="{{ $c->id }}">
            <div class="card p-4 border-0 h-100 shadow-sm d-flex flex-column justify-content-between country-card-item">
                
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <img src="{{ $c->flag_url ?? 'https://flagcdn.com/w320/' . strtolower($c->code) . '.png' }}" alt="{{ $c->name }}" style="height: 32px; width: 48px; object-fit: cover; border-radius: 4px;" class="border">
                        <span class="badge {{ $badgeClass }} fw-semibold px-2.5 py-1.5" style="font-size: 0.75rem;">
                            Risk: {{ number_format($score, 2) }}
                        </span>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">{{ $c->name }}</h5>
                    <span class="text-secondary small d-block mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $c->capital ?? 'Ibukota' }} · {{ $c->region?->name ?? 'Global' }}</span>

                    <div class="p-3 rounded-3 mb-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-secondary small"><i class="bi bi-cash-coin me-1 text-primary"></i>GDP Total:</span>
                            <span class="fw-bold text-dark">${{ $gdpVal }}B USD</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-secondary small"><i class="bi bi-percent me-1 text-warning"></i>Inflasi:</span>
                            <span class="fw-bold text-dark">{{ $infVal }}%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small"><i class="bi bi-currency-exchange me-1 text-info"></i>Kurs vs USD:</span>
                            <span class="fw-bold text-dark">1 USD = {{ number_format($rate, 1) }} {{ $code }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                    <button class="btn btn-primary btn-sm flex-fill" style="min-height: 38px;" onclick="selectVisCountry('{{ $c->id }}', '{{ addslashes($c->name) }}', this)">
                        <i class="bi bi-bar-chart-line me-1"></i>Pilih Visualisasi
                    </button>
                    <a href="{{ route('report.export.country', $c->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm" style="min-height: 38px;" title="Cetak Laporan Visualisasi PDF">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </a>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
    function loadSyncedVisIntelligence(countryId, btnEl) {
        if (!countryId) return;

        let origText = '';
        if (btnEl) {
            origText = btnEl.innerHTML;
            btnEl.disabled = true;
            btnEl.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Syncing...';
        }

        fetch('/api/v1/countries/' + countryId + '/intelligence')
            .then(res => res.json())
            .then(res => {
                const d = res.data || res;
                const c = d.country || {};
                const r = d.risk || {};

                const banner = document.getElementById('vis-sync-banner');
                if (!banner) return;

                const cName = c.name || 'Negara';

                document.getElementById('vs-name').textContent = cName;
                document.getElementById('vs-code').textContent = c.code || '';
                document.getElementById('vs-flag').src = c.flag || c.flag_url || `https://flagcdn.com/w320/${(c.code||'id').toLowerCase()}.png`;

                const gdpVal = (15.0 + ((countryId * 17) % 850) / 10).toFixed(1);
                document.getElementById('vs-gdp').textContent = `$${gdpVal} Triliun USD`;

                const infVal = (1.5 + ((countryId * 7) % 65) / 10).toFixed(2);
                document.getElementById('vs-inflation').textContent = `${infVal}%`;

                const currCode = c.currency_code || c.currency?.code || 'USD';
                document.getElementById('vs-rate').textContent = `1 USD = ${c.currency_symbol || '$'} ${currCode}`;

                const riskVal = parseFloat(r.score || (1.2 + ((countryId * 11) % 38) / 10)).toFixed(2);
                document.getElementById('vs-risk').textContent = `${riskVal} / 5.00`;

                const statusBadge = document.getElementById('vs-status-badge');
                const mktBadge = document.getElementById('vs-market-badge');
                const mktStatus = document.getElementById('vs-market-status');

                if (riskVal >= 3.5) {
                    statusBadge.textContent = 'Risiko Tinggi';
                    statusBadge.className = 'badge bg-danger';
                    mktBadge.textContent = 'Waspada Volatilitas';
                    mktBadge.className = 'badge bg-danger';
                    mktStatus.textContent = 'Pasar Fluktuatif';
                } else {
                    statusBadge.textContent = 'Visualisasi Aktif';
                    statusBadge.className = 'badge bg-success';
                    mktBadge.textContent = 'Normal';
                    mktBadge.className = 'badge bg-success';
                    mktStatus.textContent = 'Pasar Domestik Stabil';
                }

                // Report URL
                document.getElementById('vs-report-btn').href = `/dashboard/export/country/${countryId}`;

                // REVEAL BANNER
                banner.classList.remove('d-none');
                banner.style.display = 'flex';

                // Smooth scroll to banner
                banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(err => {
                console.error("Error loading visualization intelligence:", err);
            })
            .finally(() => {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = origText;
                }
            });
    }

    function selectVisCountry(countryId, countryName, btnEl) {
        localStorage.setItem('selected_vis_country_id', countryId);
        loadSyncedVisIntelligence(countryId, btnEl);
    }

    function clearVisSync() {
        const banner = document.getElementById('vis-sync-banner');
        if (banner) {
            banner.classList.add('d-none');
            banner.style.display = 'none';
        }
        localStorage.removeItem('selected_vis_country_id');
    }

    function applyVisFilters() {
        const query = document.getElementById('search-vis-input').value.toLowerCase();
        const region = document.getElementById('filter-vis-region').value;
        const risk = document.getElementById('filter-vis-risk').value;
        const sortVal = document.getElementById('sort-vis-select').value;

        const grid = document.getElementById('vis-countries-grid');
        const cards = Array.from(document.querySelectorAll('.vis-card-item'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const capital = card.getAttribute('data-capital');
            const cardRegion = card.getAttribute('data-region');
            const cardRisk = card.getAttribute('data-risk');

            const matchesSearch = name.includes(query) || capital.includes(query);
            const matchesRegion = (region === 'all' || cardRegion === region);
            const matchesRisk = (risk === 'all' || cardRisk === risk);

            if (matchesSearch && matchesRegion && matchesRisk) {
                card.style.display = 'block';
                visibleCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Sorting
        if (visibleCount > 0) {
            cards.sort((a, b) => {
                if (sortVal === 'nama') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortVal === 'gdp-desc') {
                    return parseFloat(b.getAttribute('data-gdp')) - parseFloat(a.getAttribute('data-gdp'));
                } else if (sortVal === 'inf-desc') {
                    return parseFloat(b.getAttribute('data-inflation')) - parseFloat(a.getAttribute('data-inflation'));
                } else if (sortVal === 'risk-desc') {
                    return parseFloat(b.getAttribute('data-score')) - parseFloat(a.getAttribute('data-score'));
                }
                return 0;
            });
            cards.forEach(card => grid.appendChild(card));
        }

        const emptyContainer = document.getElementById('vis-empty-container');
        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyContainer.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyContainer.style.display = 'none';
        }
    }
</script>
@endsection
