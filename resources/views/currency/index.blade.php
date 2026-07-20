<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Global Exchange Rate Center</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-cash-stack text-primary me-2"></i>Global Exchange Rate Center</h3>
                        <p class="text-secondary small mb-0">Pantau pergerakan nilai tukar mata uang 250 negara secara real-time untuk mendukung analisis rantai pasok global.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Summary (4 KPI Cards - Top Position) -->
    <div class="row g-4 mb-4">
        <!-- 1. Total Negara / Valuta Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Total Mata Uang Dipantau</span>
                    <h3 class="fw-bold text-dark mb-1" id="kpi-currency-total">{{ $countries->count() }}</h3>
                    <span class="text-secondary small d-block" style="font-size: 0.725rem;">Cakupan Valuta Global 250 Negara</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-cash-coin fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 2. Mata Uang Menguat -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Mata Uang Menguat</span>
                    <h3 class="fw-bold text-success mb-1" id="kpi-currency-gainers">142</h3>
                    <span class="text-success small d-block" style="font-size: 0.725rem;"><i class="bi bi-graph-up me-1"></i>Tren Harian Positif</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-caret-up-square-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 3. Mata Uang Melemah -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Mata Uang Melemah</span>
                    <h3 class="fw-bold text-danger mb-1" id="kpi-currency-losers">108</h3>
                    <span class="text-danger small d-block" style="font-size: 0.725rem;"><i class="bi bi-graph-down me-1"></i>Tren Harian Negatif</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.08); color: var(--danger);">
                    <i class="bi bi-caret-down-square-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 4. Update Terakhir -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Update Terakhir</span>
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.15rem;">Hari Ini (WIB)</h4>
                    <span class="text-secondary small d-block" style="font-size: 0.725rem;"><i class="bi bi-cpu me-1"></i>Sinkronisasi Terhubung</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(6, 182, 212, 0.08); color: var(--info);">
                    <i class="bi bi-clock-history fs-3"></i>
                </div>
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
                            <input type="text" id="search-currency-input" placeholder="Cari negara, mata uang, atau kode valuta..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyCurrencyFilters()">
                        </div>
                    </div>

                    <!-- Region Filter -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-currency-region" class="form-select" style="min-height: 44px;" onchange="applyCurrencyFilters()">
                            <option value="all">Semua Wilayah</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="africa">Afrika</option>
                            <option value="americas">Amerika</option>
                            <option value="oceania">Oceania</option>
                        </select>
                    </div>

                    <!-- Trend Filter -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-currency-trend" class="form-select" style="min-height: 44px;" onchange="applyCurrencyFilters()">
                            <option value="all">Semua Pergerakan Kurs</option>
                            <option value="gainer">📈 Menguat (Positif)</option>
                            <option value="loser">📉 Melemah (Negatif)</option>
                        </select>
                    </div>

                    <!-- Sorting -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                        <select id="sort-currency-select" class="form-select" style="min-height: 44px;" onchange="applyCurrencyFilters()">
                            <option value="nama">Urutkan: Nama Negara</option>
                            <option value="code">Urutkan: Kode Valuta</option>
                            <option value="gainer">Urutkan: Tren Menguat</option>
                            <option value="loser">Urutkan: Tren Melemah</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SYNCED CURRENCY INTELLIGENCE BANNER (5 KEY METRICS - HIDDEN BY DEFAULT) -->
    <div id="currency-sync-banner" class="row g-4 mb-4 d-none">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1E3A8A 0%, #1D4ED8 50%, #2563EB 100%); color: #FFFFFF; border-radius: 16px;">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.2) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <img id="curs-flag" src="https://flagcdn.com/w320/id.png" alt="Flag" style="height: 38px; width: 56px; object-fit: cover; border-radius: 6px;" class="border border-light">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="fw-bold text-white mb-0" id="curs-name">Negara</h4>
                                <span class="badge bg-light text-primary" id="curs-code">CODE</span>
                                <span class="badge bg-success" id="curs-status-badge">Menguat (+0.45%)</span>
                            </div>
                            <span class="text-white-50 small">Hasil Sinkronisasi & Intelijen Valuta Terpilih (Pilih & Sync Currency)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="curs-report-btn" href="#" target="_blank" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan Keuangan PDF
                        </a>
                        <button class="btn btn-outline-light btn-sm" onclick="clearCurrencySync()">
                            <i class="bi bi-x-circle me-1"></i> Tutup Panel Currency
                        </button>
                    </div>
                </div>

                <!-- 5 INDIKATOR KUNCI CURRENCY -->
                <div class="row g-3">
                    <!-- 1. Kurs Acuan USD -->
                    <div class="col-xl-3 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-currency-exchange me-1"></i>Kurs Acuan USD</span>
                            <h5 class="fw-bold text-white mb-0" id="curs-rate">1 USD = Rp 16.250</h5>
                            <span class="text-white-50 extra-small d-block mt-1" id="curs-symbol" style="font-size: 0.72rem;">Nilai Tukar Terkini</span>
                        </div>
                    </div>
                    <!-- 2. Perubahan Harian -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-graph-up-arrow me-1"></i>Perubahan Harian</span>
                            <h5 class="fw-bold text-white mb-0" id="curs-change">+0.45%</h5>
                            <span class="text-white-50 extra-small d-block mt-1" id="curs-trend-label" style="font-size: 0.72rem;">Tren Positif</span>
                        </div>
                    </div>
                    <!-- 3. Valuta & Simbol -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-cash-stack me-1"></i>Kode & Simbol</span>
                            <h5 class="fw-bold text-white mb-0" id="curs-curr-code">IDR (Rp)</h5>
                            <span class="text-white-50 extra-small d-block mt-1" id="curs-curr-name" style="font-size: 0.72rem;">Indonesian Rupiah</span>
                        </div>
                    </div>
                    <!-- 4. Populasi Negara -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-people-fill me-1"></i>Populasi</span>
                            <h5 class="fw-bold text-white mb-0" id="curs-pop">273.5 Juta</h5>
                            <span class="text-white-50 extra-small d-block mt-1" style="font-size: 0.72rem;">Pasar Domestik</span>
                        </div>
                    </div>
                    <!-- 5. Dampak Logistik -->
                    <div class="col-xl-3 col-md-8 col-12">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-shield-check me-1"></i>Risiko Rantai Pasok Impor</span>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold text-white mb-0" id="curs-impact">Stabilitas Biaya Impor Logistik</h6>
                                <span class="badge bg-success text-white" id="curs-impact-level">Risiko Rendah</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State Container -->
    <div id="currency-empty-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
                <i class="bi bi-search text-secondary fs-1 mb-2"></i>
                <h5 class="fw-bold text-dark mb-1">Tidak ada valuta negara yang ditemukan.</h5>
                <p class="text-secondary small mb-3">Silakan atur kembali kata kunci pencarian atau penyaringan filter Anda.</p>
            </div>
        </div>
    </div>

    <!-- 250 Countries Currency Cards Grid -->
    <div id="currency-countries-grid" class="row g-4 mb-4">
        @foreach ($countries as $c)
        @php
            $curr = $c->currency;
            $code = $curr?->code ?? 'USD';
            $symbol = $curr?->symbol ?? '$';
            $currName = $curr?->name ?? 'US Dollar';

            // Deterministic exchange rate calculation for demo
            $baseRate = 1.0;
            if ($code === 'IDR') $baseRate = 16250.0;
            else if ($code === 'EUR') $baseRate = 0.92;
            else if ($code === 'JPY') $baseRate = 155.4;
            else if ($code === 'GBP') $baseRate = 0.78;
            else if ($code === 'SGD') $baseRate = 1.35;
            else if ($code === 'CNY') $baseRate = 7.24;
            else $baseRate = round(0.5 + (($c->id * 13) % 80) + (($c->id * 3) % 100) / 100, 2);

            $changePct = round(-2.5 + (($c->id * 7) % 55) / 10, 2);
            $isGainer = $changePct >= 0;
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-6 currency-card-item" 
             data-name="{{ strtolower($c->name) }}" 
             data-code="{{ strtolower($code) }}"
             data-capital="{{ strtolower($c->capital ?? '') }}"
             data-region="{{ strtolower($c->region?->name ?? 'asia') }}"
             data-change="{{ $changePct }}"
             data-gainer="{{ $isGainer ? '1' : '0' }}">
            <div class="card p-4 border-0 h-100 shadow-sm d-flex flex-column justify-content-between country-card-item">
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <img src="{{ $c->flag_url ?? 'https://flagcdn.com/w320/' . strtolower($c->code) . '.png' }}" alt="{{ $c->name }}" style="height: 32px; width: 48px; object-fit: cover; border-radius: 4px;" class="border">
                    <span class="badge {{ $isGainer ? 'bg-success' : 'bg-danger' }} fw-semibold px-2.5 py-1.5" style="font-size: 0.75rem;">
                        <i class="bi {{ $isGainer ? 'bi-graph-up-arrow' : 'bi-graph-down-arrow' }} me-1"></i>
                        {{ $isGainer ? '+' : '' }}{{ number_format($changePct, 2) }}%
                    </span>
                </div>

                <h5 class="fw-bold text-dark mb-1">{{ $c->name }}</h5>
                <span class="text-secondary small d-block mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $c->capital ?? 'Ibukota' }} · {{ $c->region?->name ?? 'Global' }}</span>

                <div class="p-3 rounded-3 mb-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Kode & Simbol:</span>
                        <span class="fw-bold text-primary">{{ $code }} ({{ $symbol }})</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Nama Valuta:</span>
                        <span class="fw-bold text-dark text-truncate" style="max-width: 130px;">{{ $currName }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-secondary small">Kurs vs USD:</span>
                        <span class="fw-bold text-dark">1 USD = {{ number_format($baseRate, 2) }} {{ $code }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-secondary small">Tren Harian:</span>
                        <span class="fw-bold {{ $isGainer ? 'text-success' : 'text-danger' }}">{{ $isGainer ? 'Menguat' : 'Melemah' }}</span>
                    </div>
                </div>

                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                    <button class="btn btn-primary btn-sm flex-fill" style="min-height: 38px;" onclick="selectCurrencyCountry('{{ $c->id }}', this)">
                        <i class="bi bi-currency-exchange me-1"></i>Pilih & Sync Currency
                    </button>
                    <a href="{{ route('report.export.country', $c->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm" style="min-height: 38px;" title="Cetak Laporan Keuangan PDF">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </a>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
    function loadSyncedCurrencyIntelligence(countryId, btnEl) {
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
                const ex = d.exchange_rate || {};
                const r = d.risk || {};

                const banner = document.getElementById('currency-sync-banner');
                if (!banner) return;

                document.getElementById('curs-name').textContent = c.name || 'Negara';
                document.getElementById('curs-code').textContent = c.code || '';
                document.getElementById('curs-flag').src = c.flag || c.flag_url || `https://flagcdn.com/w320/${(c.code||'id').toLowerCase()}.png`;

                const currCode = ex.target || c.currency_code || c.currency?.code || 'USD';
                const rateVal = parseFloat(ex.rate || 1);
                
                document.getElementById('curs-rate').textContent = `1 USD = ${new Intl.NumberFormat('id-ID').format(rateVal)} ${currCode}`;
                document.getElementById('curs-symbol').textContent = `Simbol: ${c.currency_symbol || c.currency?.symbol || '$'}`;

                // Change %
                const changePct = parseFloat(((-2.5 + ((countryId * 7) % 55) / 10)).toFixed(2));
                const isGainer = changePct >= 0;
                
                document.getElementById('curs-change').textContent = (isGainer ? '+' : '') + changePct + '%';
                document.getElementById('curs-change').className = `fw-bold ${isGainer ? 'text-success' : 'text-danger'} mb-0`;
                document.getElementById('curs-trend-label').textContent = isGainer ? '📈 Tren Menguat' : '📉 Tren Melemah';

                document.getElementById('curs-curr-code').textContent = `${currCode} (${c.currency_symbol || '$'})`;
                document.getElementById('curs-curr-name').textContent = c.currency?.name || 'Mata Uang Acuan';

                // Population
                const popVal = parseInt(c.population || 1000000);
                document.getElementById('curs-pop').textContent = (popVal >= 1e6 ? (popVal / 1e6).toFixed(1) + ' Juta' : new Intl.NumberFormat('id-ID').format(popVal));

                // Impact
                const impactBadge = document.getElementById('curs-impact-level');
                if (changePct < -1.5) {
                    document.getElementById('curs-impact').textContent = 'Risiko Fluktuasi Biaya Impor';
                    impactBadge.textContent = 'Risiko Tinggi';
                    impactBadge.className = 'badge bg-danger';
                } else if (changePct < 0) {
                    document.getElementById('curs-impact').textContent = 'Pengawasan Kurs Impor';
                    impactBadge.textContent = 'Risiko Sedang';
                    impactBadge.className = 'badge bg-warning text-dark';
                } else {
                    document.getElementById('curs-impact').textContent = 'Stabilitas Biaya Impor Logistik';
                    impactBadge.textContent = 'Risiko Rendah';
                    impactBadge.className = 'badge bg-success';
                }

                // Status Badge
                const statusBadge = document.getElementById('curs-status-badge');
                statusBadge.textContent = `${isGainer ? '📈 Menguat' : '📉 Melemah'} (${(isGainer ? '+' : '') + changePct}%)`;
                statusBadge.className = `badge bg-${isGainer ? 'success' : 'danger'}`;

                // Report URL
                document.getElementById('curs-report-btn').href = `/dashboard/export/country/${countryId}`;

                // REVEAL BANNER
                banner.classList.remove('d-none');
                banner.style.display = 'flex';

                // Smooth scroll to banner
                banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(err => {
                console.error("Error loading currency intelligence:", err);
            })
            .finally(() => {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = origText;
                }
            });
    }

    function selectCurrencyCountry(countryId, btnEl) {
        localStorage.setItem('selected_currency_country_id', countryId);
        loadSyncedCurrencyIntelligence(countryId, btnEl);
    }

    function clearCurrencySync() {
        const banner = document.getElementById('currency-sync-banner');
        if (banner) {
            banner.classList.add('d-none');
            banner.style.display = 'none';
        }
        localStorage.removeItem('selected_currency_country_id');
    }

    function applyCurrencyFilters() {
        const query = document.getElementById('search-currency-input').value.toLowerCase();
        const region = document.getElementById('filter-currency-region').value;
        const trend = document.getElementById('filter-currency-trend').value;
        const sortVal = document.getElementById('sort-currency-select').value;

        const grid = document.getElementById('currency-countries-grid');
        const cards = Array.from(document.querySelectorAll('.currency-card-item'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const code = card.getAttribute('data-code');
            const capital = card.getAttribute('data-capital');
            const cardRegion = card.getAttribute('data-region');
            const isGainer = card.getAttribute('data-gainer') === '1';

            const matchesSearch = name.includes(query) || code.includes(query) || capital.includes(query);
            const matchesRegion = (region === 'all' || cardRegion === region);

            let matchesTrend = true;
            if (trend === 'gainer') matchesTrend = isGainer;
            else if (trend === 'loser') matchesTrend = !isGainer;

            if (matchesSearch && matchesRegion && matchesTrend) {
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
                } else if (sortVal === 'code') {
                    return a.getAttribute('data-code').localeCompare(b.getAttribute('data-code'));
                } else if (sortVal === 'gainer') {
                    return parseFloat(b.getAttribute('data-change')) - parseFloat(a.getAttribute('data-change'));
                } else if (sortVal === 'loser') {
                    return parseFloat(a.getAttribute('data-change')) - parseFloat(b.getAttribute('data-change'));
                }
                return 0;
            });
            cards.forEach(card => grid.appendChild(card));
        }

        const emptyContainer = document.getElementById('currency-empty-container');
        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyContainer.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyContainer.style.display = 'none';
        }
    }
</script>
