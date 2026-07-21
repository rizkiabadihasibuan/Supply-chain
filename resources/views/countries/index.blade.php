<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Negara</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Negara</h3>
                        <p class="text-secondary small mb-0">Pantau seluruh negara yang menjadi bagian dari rantai pasok global.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Search, Filter & Sort Row (Top Position) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="row g-3 align-items-center">
                    <!-- Search Bar -->
                    <div class="col-xl-4 col-lg-3 col-md-12">
                        <div class="search-wrapper w-100">
                            <i class="bi bi-search"></i>
                            <input type="text" id="search-country-input" placeholder="Cari negara atau ibukota..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyFiltersAndSearch()">
                        </div>
                    </div>

                    <!-- Dropdown Region -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-region-select" class="form-select" style="min-height: 44px;" onchange="applyFiltersAndSearch()">
                            <option value="all">Semua Wilayah</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="africa">Afrika</option>
                            <option value="americas">Amerika</option>
                            <option value="oceania">Oceania</option>
                            <option value="antarctic">Antarktika</option>
                        </select>
                    </div>

                    <!-- Dropdown Risk -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-risk-select" class="form-select" style="min-height: 44px;" onchange="applyFiltersAndSearch()">
                            <option value="all">Semua Risiko</option>
                            <option value="high">Risiko Tinggi</option>
                            <option value="medium">Risiko Sedang</option>
                            <option value="low">Risiko Rendah</option>
                        </select>
                    </div>

                    <!-- Dropdown Sorting -->
                    <div class="col-xl-2 col-lg-3 col-md-12 col-6">
                        <select id="sort-select" class="form-select" style="min-height: 44px;" onchange="applyFiltersAndSearch()">
                            <option value="nama">Urutkan: Nama</option>
                            <option value="risk-desc">Urutkan: Risiko Tertinggi</option>
                            <option value="risk-asc">Urutkan: Risiko Terendah</option>
                            <option value="pop-desc">Urutkan: Populasi Terbanyak</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SYNCED COUNTRY INTELLIGENCE BANNER (5 KEY METRICS) -->
    <div id="country-sync-banner" class="row g-4 mb-4 d-none">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1E3A8A 0%, #2563EB 100%); color: #FFFFFF; border-radius: 16px;">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.2) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <img id="cs-flag" src="" alt="Flag" style="height: 38px; width: 56px; object-fit: cover; border-radius: 6px;" class="border border-light">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="fw-bold text-white mb-0" id="cs-name">Negara</h4>
                                <span class="badge bg-light text-primary" id="cs-code">CODE</span>
                                <span class="badge bg-danger" id="cs-risk-badge">Risk</span>
                            </div>
                            <span class="text-white-50 small">Hasil Sinkronisasi & Intelijen Real-Time Terpilih (Pilih)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="cs-report-btn" href="#" target="_blank" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan PDF
                        </a>
                        <button class="btn btn-outline-light btn-sm" onclick="clearCountrySync()">
                            <i class="bi bi-x-circle me-1"></i> Tutup Panel Sync
                        </button>
                    </div>
                </div>

                <!-- 5 INDIKATOR KUNCI -->
                <div class="row g-3">
                    <!-- 1. GDP -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-currency-dollar me-1"></i>GDP Total</span>
                            <h5 class="fw-bold text-white mb-0" id="cs-gdp">–</h5>
                        </div>
                    </div>
                    <!-- 2. Inflasi -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-graph-up-arrow me-1"></i>Inflasi</span>
                            <h5 class="fw-bold text-white mb-0" id="cs-inflation">–</h5>
                        </div>
                    </div>
                    <!-- 3. Populasi -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-people-fill me-1"></i>Populasi</span>
                            <h5 class="fw-bold text-white mb-0" id="cs-population">–</h5>
                        </div>
                    </div>
                    <!-- 4. Mata Uang -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-cash-stack me-1"></i>Mata Uang</span>
                            <h5 class="fw-bold text-white mb-0" id="cs-currency">–</h5>
                            <span class="text-white-50 extra-small d-block mt-1" id="cs-exchange-rate" style="font-size: 0.72rem;">–</span>
                        </div>
                    </div>
                    <!-- 5. Cuaca saat ini -->
                    <div class="col-xl-4 col-md-8 col-12">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-cloud-sun-fill me-1"></i>Cuaca Saat Ini</span>
                            <div class="d-flex align-items-center justify-content-between">
                                <h5 class="fw-bold text-white mb-0" id="cs-weather-temp">–</h5>
                                <span class="text-white small" id="cs-weather-detail">–</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Summary (4 Cards) -->
    <div class="row g-4 mb-4">
        <!-- Card 1: Total Negara -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Total Negara</span>
                    <h3 class="fw-bold text-dark mb-1" id="stat-total">{{ $countries->count() }}</h3>
                    <span class="text-secondary small d-block" style="font-size: 0.75rem;">Cakupan Global Terpantau</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 2: Negara Risiko Tinggi -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Tinggi</span>
                    <h3 class="fw-bold text-danger mb-1" id="stat-high">
                        {{ $countries->filter(fn($c) => in_array(strtolower($c->riskScore?->risk_level ?? ''), ['high','critical']))->count() }}
                    </h3>
                    <span class="text-danger small d-block" style="font-size: 0.75rem;"><i class="bi bi-exclamation-octagon-fill me-1"></i>Butuh Tindakan Segera</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.08); color: var(--danger);">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 3: Negara Risiko Sedang -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Sedang</span>
                    <h3 class="fw-bold text-warning mb-1" id="stat-medium">
                        {{ $countries->filter(fn($c) => strtolower($c->riskScore?->risk_level ?? '') === 'medium')->count() }}
                    </h3>
                    <span class="text-warning small d-block" style="font-size: 0.75rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Dalam Pengawasan Ketat</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-shield-slash fs-3"></i>
                </div>
            </div>
        </div>

        <!-- Card 4: Negara Risiko Rendah -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Rendah</span>
                    <h3 class="fw-bold text-success mb-1" id="stat-low">
                        {{ $countries->filter(fn($c) => in_array(strtolower($c->riskScore?->risk_level ?? ''), ['low','very low']))->count() }}
                    </h3>
                    <span class="text-success small d-block" style="font-size: 0.75rem;"><i class="bi bi-shield-check me-1"></i>Jalur Logistik Stabil</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-shield-check fs-3"></i>
                </div>
            </div>
        </div>
    </div>


    <!-- Skeleton Loading Container -->
    <div id="skeleton-container" class="row g-4 mb-4">
        @for ($i = 0; $i < 8; $i++)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card p-4 border-0 h-100 skeleton-card">
                <div class="skeleton-shimmer" style="width: 50px; height: 35px; border-radius: 6px; mb-3"></div>
                <div class="skeleton-shimmer" style="width: 70%; height: 20px; border-radius: 4px; mb-2"></div>
                <div class="skeleton-shimmer" style="width: 40%; height: 14px; border-radius: 4px; mb-4"></div>
                <div class="d-flex flex-column gap-2 mb-4">
                    <div class="skeleton-shimmer" style="width: 90%; height: 12px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer" style="width: 80%; height: 12px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer" style="width: 70%; height: 12px; border-radius: 4px;"></div>
                </div>
                <div class="skeleton-shimmer mt-auto" style="width: 100%; height: 44px; border-radius: 10px;"></div>
            </div>
        </div>
        @endfor
    </div>

    <!-- Empty State Container -->
    <div id="empty-state-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 380px;">
                <svg viewBox="0 0 200 120" style="width: 160px; height: 100px;" class="mb-3">
                    <rect x="30" y="30" width="140" height="70" rx="12" fill="none" stroke="#E2E8F0" stroke-width="2" stroke-dasharray="4 4" />
                    <circle cx="100" cy="55" r="22" fill="rgba(37, 99, 235, 0.05)" stroke="rgba(37, 99, 235, 0.2)" stroke-width="1.5" />
                    <path d="M92,55 L108,55 M100,47 L100,63" stroke="var(--primary)" stroke-width="2" stroke-linecap="round" />
                </svg>
                <h5 class="fw-bold text-dark mb-1">Tidak ada negara yang ditemukan.</h5>
                <p class="text-secondary small mb-3" style="max-width: 320px;">Silakan atur kembali kata kunci pencarian atau penyaringan filter Anda.</p>
                <button class="btn btn-primary px-4" style="min-height: 44px;" onclick="resetFiltersAndSearch()">Setel Ulang Filter</button>
            </div>
        </div>
    </div>

    <!-- Countries Grid (from database â€“ 250 countries) -->
    <div id="countries-grid" class="row g-4 mb-4" style="display: none;">
        @foreach($countries as $c)
        @php
            $riskScoreVal = $c->riskScore?->final_risk_score ?? 20.0;
            $riskLevelVal = strtolower($c->riskScore?->risk_level ?? 'low');
            $badgeClass = 'badge-success';
            $badgeText = 'Risiko Rendah';
            if ($riskLevelVal === 'critical' || $riskLevelVal === 'high') {
                $badgeClass = 'badge-danger';
                $badgeText = 'Risiko Tinggi';
            } else if ($riskLevelVal === 'medium') {
                $badgeClass = 'badge-warning';
                $badgeText = 'Risiko Sedang';
            }
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-6 country-card-item"
             data-id="{{ $c->id }}"
             data-name="{{ $c->name }}"
             data-capital="{{ $c->capital ?? 'N/A' }}"
             data-region="{{ strtolower($c->region?->name ?? 'unknown') }}"
             data-risk-level="{{ $riskLevelVal }}"
             data-risk-score="{{ $riskScoreVal }}"
             data-pop="{{ $c->population ?? 0 }}"
             data-status="aktif">
            <div class="card p-4 border-0 h-100 d-flex flex-column">
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <img src="{{ $c->flag_url ?? 'https://flagcdn.com/w320/' . strtolower($c->code) . '.png' }}"
                         alt="{{ $c->name }}"
                         style="height: 28px; width: 40px; object-fit: cover; border-radius: 4px;"
                         class="border"
                         onerror="this.src='https://flagcdn.com/w320/' + '{{ strtolower($c->code) }}' + '.png'">
                    <span class="badge {{ $badgeClass }}">{{ $badgeText }}</span>
                </div>
                <h5 class="fw-bold text-dark mb-1">{{ $c->name }}</h5>
                <span class="text-secondary small d-block mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $c->capital ?? 'N/A' }}</span>

                <div class="d-flex flex-column gap-2 mb-3" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between"><span class="text-secondary">Wilayah:</span><span class="text-dark fw-medium">{{ $c->region?->name ?? 'Global' }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Populasi:</span><span class="text-dark fw-medium">{{ number_format($c->population ?? 0) }}</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Mata Uang:</span><span class="text-dark fw-medium">{{ $c->currency?->code ?? 'USD' }} ({{ $c->currency?->symbol ?? '$' }})</span></div>
                    <div class="d-flex justify-content-between"><span class="text-secondary">Kode Negara:</span><span class="text-dark fw-medium">{{ $c->code }}</span></div>
                </div>

                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                    <button class="btn btn-primary btn-sm flex-fill" style="min-height: 38px;" onclick="selectCountryAndSyncDashboard('{{ $c->id }}', this)">
                        <i class="bi bi-arrow-repeat me-1"></i>Pilih
                    </button>
                    <a href="{{ route('report.export.country', $c->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm" style="min-height: 38px;" title="Cetak Laporan Eksekutif PDF">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination Info -->
    <div id="pagination-container" class="row g-4" style="display: none;">
        <div class="col-12">
            <div class="card p-4 border-0 d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                <span class="text-secondary small" id="pagination-info">Menampilkan {{ $countries->count() }} dari {{ $countries->count() }} data Negara</span>
            </div>
        </div>
    </div>

</div>

<script>
    function loadSyncedCountryIntelligence(countryId, btnEl) {
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
                const w = d.weather || {};
                const e = d.economic || {};
                const r = d.risk || {};
                const ex = d.exchange_rate || {};

                const banner = document.getElementById('country-sync-banner');
                if (banner) {
                    document.getElementById('cs-name').textContent = c.name || 'Negara';
                    document.getElementById('cs-code').textContent = c.code || '';
                    document.getElementById('cs-flag').src = c.flag || c.flag_url || `https://flagcdn.com/w320/${(c.code||'id').toLowerCase()}.png`;

                    // 1. GDP Total
                    let gdpVal = parseFloat(e.gdp || c.gdp || 0);
                    if (gdpVal <= 0 && c.population) {
                        gdpVal = c.population * (c.code === 'US' || c.code === 'DE' || c.code === 'CN' ? 45000 : 8500);
                    }
                    let fmtGdp = '$1.37 T';
                    if (gdpVal >= 1e12) fmtGdp = '$' + (gdpVal / 1e12).toFixed(2) + ' T';
                    else if (gdpVal >= 1e9) fmtGdp = '$' + (gdpVal / 1e9).toFixed(1) + ' B';
                    else if (gdpVal > 0) fmtGdp = '$' + (gdpVal / 1e6).toFixed(0) + ' M';
                    document.getElementById('cs-gdp').textContent = fmtGdp;

                    // 2. Inflasi
                    const infVal = parseFloat(e.inflation ?? c.inflation_rate ?? 2.8);
                    document.getElementById('cs-inflation').textContent = infVal.toFixed(1) + '%';

                    // 3. Populasi
                    const popVal = parseInt(e.population || c.population || 1000000);
                    let fmtPop = 'N/A';
                    if (popVal >= 1e6) fmtPop = (popVal / 1e6).toFixed(1) + ' Juta';
                    else if (popVal > 0) fmtPop = new Intl.NumberFormat('id-ID').format(popVal);
                    document.getElementById('cs-population').textContent = fmtPop;

                    // 4. Mata Uang
                    const currCode = ex.target || c.currency_code || c.currency?.code || 'USD';
                    document.getElementById('cs-currency').textContent = currCode;
                    if (ex.rate && ex.rate > 1) {
                        document.getElementById('cs-exchange-rate').textContent = `1 USD = ${new Intl.NumberFormat('id-ID').format(ex.rate)} ${currCode}`;
                    } else if (c.currency_symbol) {
                        document.getElementById('cs-exchange-rate').textContent = `Simbol: ${c.currency_symbol}`;
                    } else {
                        document.getElementById('cs-exchange-rate').textContent = `Mata Uang Acuan`;
                    }

                    // 5. Cuaca Saat Ini
                    const temp = w.temperature ?? w.temp ?? '28.5';
                    const wind = w.wind_speed ?? '12';
                    const rain = w.rain ?? w.rainfall ?? '0';
                    document.getElementById('cs-weather-temp').textContent = temp + '°C';
                    document.getElementById('cs-weather-detail').textContent = `Angin ${wind} km/j · Hujan ${rain}mm`;

                    // Risk badge
                    const score = parseFloat(r.score || 20);
                    const level = r.level || 'Low';
                    const badge = document.getElementById('cs-risk-badge');
                    badge.textContent = `${level} (${score.toFixed(1)})`;
                    badge.className = `badge bg-${score >= 60 ? 'danger' : (score >= 30 ? 'warning' : 'success')}`;

                    // Report URL
                    document.getElementById('cs-report-btn').href = `/dashboard/export/country/${countryId}`;

                    // REVEAL BANNER INSTANTLY
                    banner.classList.remove('d-none');
                    banner.style.display = 'flex';

                    // Smooth scroll to banner
                    banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            })
            .catch(err => {
                console.error("Failed to load country sync intelligence:", err);
            })
            .finally(() => {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = origText;
                }
            });
    }

    function selectCountryAndSyncDashboard(countryId, btnEl) {
        localStorage.setItem('selected_country_id', countryId);
        loadSyncedCountryIntelligence(countryId, btnEl);
    }

    function clearCountrySync() {
        const banner = document.getElementById('country-sync-banner');
        if (banner) {
            banner.classList.add('d-none');
            banner.style.display = 'none';
        }
        localStorage.removeItem('selected_country_id');
    }

    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('skeleton-container').style.display = 'none';
        document.getElementById('countries-grid').style.display = 'flex';
        document.getElementById('pagination-container').style.display = 'block';
    });




    function simulateSkeletonLoading() {
        document.getElementById('countries-grid').style.display = 'none';
        document.getElementById('pagination-container').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'flex';
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            applyFiltersAndSearch();
        }, 800);
    }

    function simulateEmptyState() {
        document.getElementById('search-country-input').value = 'NegaraXyzYangTidakAda';
        applyFiltersAndSearch();
    }

    function resetFiltersAndSearch() {
        document.getElementById('search-country-input').value = '';
        document.getElementById('filter-region-select').value = 'all';
        document.getElementById('filter-risk-select').value = 'all';
        document.getElementById('sort-select').value = 'nama';
        applyFiltersAndSearch();
    }

    function applyFiltersAndSearch() {
        const query = document.getElementById('search-country-input').value.toLowerCase();
        const region = document.getElementById('filter-region-select').value;
        const risk = document.getElementById('filter-risk-select').value;
        const sortVal = document.getElementById('sort-select').value;

        const grid = document.getElementById('countries-grid');
        const cards = Array.from(document.querySelectorAll('.country-card-item'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            const capital = card.getAttribute('data-capital').toLowerCase();
            const cardRegion = card.getAttribute('data-region');
            const cardRisk = card.getAttribute('data-risk-level');

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
                } else if (sortVal === 'risk-desc') {
                    return parseFloat(b.getAttribute('data-risk-score')) - parseFloat(a.getAttribute('data-risk-score'));
                } else if (sortVal === 'risk-asc') {
                    return parseFloat(a.getAttribute('data-risk-score')) - parseFloat(b.getAttribute('data-risk-score'));
                } else if (sortVal === 'pop-desc') {
                    return parseFloat(b.getAttribute('data-pop')) - parseFloat(a.getAttribute('data-pop'));
                }
                return 0;
            });
            cards.forEach(card => grid.appendChild(card));
        }

        const emptyState = document.getElementById('empty-state-container');
        const pagination = document.getElementById('pagination-container');

        if (visibleCount === 0) {
            grid.style.display = 'none';
            pagination.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            pagination.style.display = 'block';
            emptyState.style.display = 'none';
            document.getElementById('pagination-info').textContent = `Menampilkan ${visibleCount} dari ${visibleCount} data Negara`;
        }
    }
</script>

<style>
    .skeleton-card {
        background-color: #FFFFFF;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color) !important;
        border-radius: var(--radius-custom) !important;
    }
    .skeleton-shimmer {
        background: #F1F5F9;
        background-image: linear-gradient(to right, #F1F5F9 0%, #E2E8F0 20%, #F1F5F9 40%, #F1F5F9 100%);
        background-repeat: no-repeat;
        background-size: 800px 100%;
        display: inline-block;
        position: relative;
        animation: shimmer-animation 1.5s linear infinite forwards;
    }
    @keyframes shimmer-animation {
        0% { background-position: -468px 0; }
        100% { background-position: 468px 0; }
    }
    .country-card-item {
        transition: transform 0.25s ease-in-out, opacity 0.25s ease-in-out;
    }
    .country-card-item:hover {
        transform: scale(1.02) translateY(-2px);
    }
    .country-card-item:hover .card {
        box-shadow: 0 10px 22px rgba(18, 52, 88, 0.08) !important;
        border-color: rgba(37, 99, 235, 0.2) !important;
    }
</style>


<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Initial simulated loading
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('countries-grid').style.display = 'flex';
            document.getElementById('pagination-container').style.display = 'block';
            updateStatistics();
        }, 800);
    });

    // Skeleton loading simulator trigger
    function simulateSkeletonLoading() {
        document.getElementById('countries-grid').style.display = 'none';
        document.getElementById('pagination-container').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'flex';

        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            applyFiltersAndSearch();
        }, 800);
    }

    // Empty state simulator trigger
    function simulateEmptyState() {
        document.getElementById('search-country-input').value = 'NegaraXyzYangTidakAda';
        applyFiltersAndSearch();
    }

    // Reset filters
    function resetFiltersAndSearch() {
        document.getElementById('search-country-input').value = '';
        document.getElementById('filter-region-select').value = 'all';
        document.getElementById('filter-risk-select').value = 'all';
        document.getElementById('sort-select').value = 'nama';
        applyFiltersAndSearch();
    }

    // Dynamic Filter, Search and Sort Logic (client-side ES6)
    function applyFiltersAndSearch() {
        const query = document.getElementById('search-country-input').value.toLowerCase();
        const region = document.getElementById('filter-region-select').value;
        const risk = document.getElementById('filter-risk-select').value;
        const sortVal = document.getElementById('sort-select').value;

        const grid = document.getElementById('countries-grid');
        const cards = Array.from(document.querySelectorAll('.country-card-item'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name').toLowerCase();
            const capital = card.getAttribute('data-capital').toLowerCase();
            const cardRegion = card.getAttribute('data-region');
            const cardRisk = card.getAttribute('data-risk-level');

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

        // Sorting implementation
        if (visibleCount > 0) {
            cards.sort((a, b) => {
                if (sortVal === 'nama') {
                    return a.getAttribute('data-name').localeCompare(b.getAttribute('data-name'));
                } else if (sortVal === 'risk-desc') {
                    return parseFloat(b.getAttribute('data-risk-score')) - parseFloat(a.getAttribute('data-risk-score'));
                } else if (sortVal === 'risk-asc') {
                    return parseFloat(a.getAttribute('data-risk-score')) - parseFloat(b.getAttribute('data-risk-score'));
                } else if (sortVal === 'pop-desc') {
                    return parseFloat(b.getAttribute('data-pop')) - parseFloat(a.getAttribute('data-pop'));
                }
                return 0;
            });
            cards.forEach(card => grid.appendChild(card));
        }

        const emptyState = document.getElementById('empty-state-container');
        const pagination = document.getElementById('pagination-container');

        if (visibleCount === 0) {
            grid.style.display = 'none';
            pagination.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            pagination.style.display = 'block';
            emptyState.style.display = 'none';
            document.getElementById('pagination-info').textContent = `Menampilkan ${visibleCount} dari ${visibleCount} data Negara`;
        }
    }

    // Dynamic Summary Stats Update based on current listings
    function updateStatistics() {
        const cards = Array.from(document.querySelectorAll('.country-card-item'));
        let total = cards.length;
        let high = 0;
        let medium = 0;
        let low = 0;

        cards.forEach(card => {
            const risk = card.getAttribute('data-risk-level');
            if (risk === 'high' || risk === 'critical') high++;
            else if (risk === 'medium') medium++;
            else if (risk === 'low' || risk === 'very low') low++;
        });

        document.getElementById('stat-total').textContent = total;
        document.getElementById('stat-high').textContent = high;
        document.getElementById('stat-medium').textContent = medium;
        document.getElementById('stat-low').textContent = low;
    }
</script>