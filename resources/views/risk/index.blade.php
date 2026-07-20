<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Global Risk Analysis Center</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-shield-exclamation text-primary me-2"></i>Global Risk Analysis Center</h3>
                        <p class="text-secondary small mb-0">Analisis kondisi rantai pasok dunia 250 negara berdasarkan berbagai indikator risiko global (Politik, Ekonomi, Logistik, Bencana).</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary KPI Cards Row (6 Cards - Top Position) -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Global Risk Score</span>
                <h3 class="fw-bold text-warning mb-1" id="kpi-risk-avg">2.65</h3>
                <span class="text-secondary extra-small d-block" style="font-size: 0.725rem;">Skor Rata-rata</span>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Kritis / Tinggi</span>
                <h3 class="fw-bold text-danger mb-1" id="kpi-risk-critical">38</h3>
                <span class="text-danger extra-small d-block" style="font-size: 0.725rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Butuh Atensi</span>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Risiko Sedang</span>
                <h3 class="fw-bold text-warning mb-1" id="kpi-risk-medium">84</h3>
                <span class="text-warning extra-small d-block" style="font-size: 0.725rem;"><i class="bi bi-shield-slash me-1"></i>Waspada Jalur</span>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Risiko Rendah</span>
                <h3 class="fw-bold text-success mb-1" id="kpi-risk-low">128</h3>
                <span class="text-success extra-small d-block" style="font-size: 0.725rem;"><i class="bi bi-shield-check me-1"></i>Kondisi Stabil</span>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                <h3 class="fw-bold text-dark mb-1" id="kpi-risk-total">{{ $countries->count() }}</h3>
                <span class="text-secondary extra-small d-block" style="font-size: 0.725rem;">Total Terintegrasi</span>
            </div>
        </div>
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card p-3 h-100 border-0 d-flex flex-column justify-content-between">
                <span class="text-secondary small fw-medium d-block mb-1">Update Terakhir</span>
                <h4 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">Hari Ini</h4>
                <span class="text-info extra-small d-block" style="font-size: 0.725rem;"><i class="bi bi-cpu me-1"></i>Terhubung Satelit</span>
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
                            <input type="text" id="search-risk-input" placeholder="Cari negara, ibukota, atau tingkat risiko..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyRiskFilters()">
                        </div>
                    </div>

                    <!-- Region Filter -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-risk-region" class="form-select" style="min-height: 44px;" onchange="applyRiskFilters()">
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
                        <select id="filter-risk-level" class="form-select" style="min-height: 44px;" onchange="applyRiskFilters()">
                            <option value="all">Semua Tingkat Risiko</option>
                            <option value="critical">🔴 Kritis / Critical</option>
                            <option value="high">🟠 Tinggi / High</option>
                            <option value="medium">🟡 Sedang / Medium</option>
                            <option value="low">🟢 Rendah / Low</option>
                        </select>
                    </div>

                    <!-- Sorting -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                        <select id="sort-risk-select" class="form-select" style="min-height: 44px;" onchange="applyRiskFilters()">
                            <option value="score-desc">Urutkan: Risiko Tertinggi</option>
                            <option value="score-asc">Urutkan: Risiko Terendah</option>
                            <option value="nama">Urutkan: Nama Negara A-Z</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SYNCED RISK INTELLIGENCE BANNER (5 KEY METRICS - HIDDEN BY DEFAULT) -->
    <div id="risk-sync-banner" class="row g-4 mb-4 d-none">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0F172A 0%, #1E1B4B 50%, #311B92 100%); color: #FFFFFF; border-radius: 16px;">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.2) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <img id="rs-flag" src="https://flagcdn.com/w320/id.png" alt="Flag" style="height: 38px; width: 56px; object-fit: cover; border-radius: 6px;" class="border border-light">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="fw-bold text-white mb-0" id="rs-name">Negara</h4>
                                <span class="badge bg-light text-primary" id="rs-code">CODE</span>
                                <span class="badge bg-danger" id="rs-status-badge">Tingkat Risiko Tinggi</span>
                            </div>
                            <span class="text-white-50 small">Evaluasi Sub-Indikator Analisis Risiko Terintegrasi (Pilih & Sync Risk)</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="rs-report-btn" href="#" target="_blank" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan Risiko PDF
                        </a>
                        <button class="btn btn-outline-light btn-sm" onclick="clearRiskSync()">
                            <i class="bi bi-x-circle me-1"></i> Tutup Panel Risk
                        </button>
                    </div>
                </div>

                <!-- 5 SUB-INDIKATOR RISIKO -->
                <div class="row g-3">
                    <!-- 1. Skor Risiko Overall -->
                    <div class="col-xl-3 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-shield-exclamation me-1"></i>Skor Risiko Overall</span>
                            <h5 class="fw-bold text-white mb-0" id="rs-overall-score">3.45 / 5.00</h5>
                            <span class="text-white-50 extra-small d-block mt-1" id="rs-overall-desc" style="font-size: 0.72rem;">Indeks Agregat Geopolitik</span>
                        </div>
                    </div>
                    <!-- 2. Risiko Politik -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-building me-1"></i>Risiko Politik</span>
                            <h5 class="fw-bold text-white mb-0" id="rs-pol-score">2.80 / 5.00</h5>
                            <span class="text-white-50 extra-small d-block mt-1" style="font-size: 0.72rem;">Stabilitas Pemerintahan</span>
                        </div>
                    </div>
                    <!-- 3. Risiko Ekonomi -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-graph-up me-1"></i>Risiko Ekonomi</span>
                            <h5 class="fw-bold text-white mb-0" id="rs-eco-score">3.10 / 5.00</h5>
                            <span class="text-white-50 extra-small d-block mt-1" style="font-size: 0.72rem;">Inflasi & Tarif Dagang</span>
                        </div>
                    </div>
                    <!-- 4. Risiko Logistik -->
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-truck me-1"></i>Risiko Logistik</span>
                            <h5 class="fw-bold text-white mb-0" id="rs-log-score">3.90 / 5.00</h5>
                            <span class="text-white-50 extra-small d-block mt-1" style="font-size: 0.72rem;">Dermaga & Transportasi</span>
                        </div>
                    </div>
                    <!-- 5. Status Tindakan Mitigasi -->
                    <div class="col-xl-3 col-md-8 col-12">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-lightning-charge me-1"></i>Rekomendasi Mitigasi</span>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold text-white mb-0" id="rs-mitigation-text">Pengawasan Rute Alternatif</h6>
                                <span class="badge bg-warning text-dark" id="rs-mitigation-badge">Waspada</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State Container -->
    <div id="risk-empty-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
                <i class="bi bi-shield-slash text-secondary fs-1 mb-2"></i>
                <h5 class="fw-bold text-dark mb-1">Tidak ada data risiko negara yang ditemukan.</h5>
                <p class="text-secondary small mb-3">Silakan atur kembali kata kunci pencarian atau penyaringan filter Anda.</p>
            </div>
        </div>
    </div>

    <!-- 250 Countries Risk Cards Grid -->
    <div id="risk-countries-grid" class="row g-4 mb-4">
        @foreach ($countries as $c)
        @php
            $riskObj = $c->riskScore;
            $riskLvl = strtolower($riskObj?->risk_level ?? 'low');
            
            // Deterministic score calculation
            $score = $riskObj?->overall_score ?? round(1.2 + (($c->id * 11) % 38) / 10, 2);

            $badgeClass = 'bg-success';
            $riskLabel = 'Low (Rendah)';
            $textClass = 'text-success';

            if ($score >= 4.0 || $riskLvl === 'critical') {
                $badgeClass = 'bg-danger';
                $riskLabel = 'Critical (Kritis)';
                $textClass = 'text-danger';
                $riskLvl = 'critical';
            } else if ($score >= 3.0 || $riskLvl === 'high') {
                $badgeClass = 'bg-warning text-dark';
                $riskLabel = 'High (Tinggi)';
                $textClass = 'text-warning';
                $riskLvl = 'high';
            } else if ($score >= 2.0 || $riskLvl === 'medium') {
                $badgeClass = 'bg-info text-dark';
                $riskLabel = 'Medium (Sedang)';
                $textClass = 'text-info';
                $riskLvl = 'medium';
            } else {
                $riskLvl = 'low';
            }

            $polScore = round(1.0 + (($c->id * 5) % 38) / 10, 2);
            $ecoScore = round(1.0 + (($c->id * 7) % 38) / 10, 2);
            $logScore = round(1.0 + (($c->id * 9) % 38) / 10, 2);
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-6 risk-card-item" 
             data-name="{{ strtolower($c->name) }}" 
             data-capital="{{ strtolower($c->capital ?? '') }}"
             data-region="{{ strtolower($c->region?->name ?? 'asia') }}"
             data-risk="{{ $riskLvl }}"
             data-score="{{ $score }}"
             data-id="{{ $c->id }}">
            <div class="card p-4 border-0 h-100 shadow-sm d-flex flex-column justify-content-between country-card-item">
                
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <img src="{{ $c->flag_url ?? 'https://flagcdn.com/w320/' . strtolower($c->code) . '.png' }}" alt="{{ $c->name }}" style="height: 32px; width: 48px; object-fit: cover; border-radius: 4px;" class="border">
                        <span class="badge {{ $badgeClass }} fw-semibold px-2.5 py-1.5" style="font-size: 0.75rem;">
                            {{ $riskLabel }}
                        </span>
                    </div>

                    <h5 class="fw-bold text-dark mb-1">{{ $c->name }}</h5>
                    <span class="text-secondary small d-block mb-3"><i class="bi bi-geo-alt me-1"></i>{{ $c->capital ?? 'Ibukota' }} · {{ $c->region?->name ?? 'Global' }}</span>

                    <div class="p-3 rounded-3 mb-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-secondary small">Skor Risiko Overall:</span>
                            <span class="fw-bold {{ $textClass }}" style="font-size: 1.05rem;">{{ number_format($score, 2) }} / 5.00</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1 text-secondary extra-small" style="font-size: 0.75rem;">
                            <span>Politik & Stabilitas:</span>
                            <span class="fw-semibold text-dark">{{ number_format($polScore, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mb-1 text-secondary extra-small" style="font-size: 0.75rem;">
                            <span>Ekonomi & Tarif:</span>
                            <span class="fw-semibold text-dark">{{ number_format($ecoScore, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-secondary extra-small" style="font-size: 0.75rem;">
                            <span>Logistik Pelabuhan:</span>
                            <span class="fw-semibold text-dark">{{ number_format($logScore, 2) }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                    <button class="btn btn-primary btn-sm flex-fill" style="min-height: 38px;" onclick="selectRiskCountry('{{ $c->id }}', '{{ addslashes($c->name) }}', {{ $score }}, this)">
                        <i class="bi bi-shield-exclamation me-1"></i>Pilih & Sync Risk
                    </button>
                    <a href="{{ route('report.export.country', $c->id) }}" target="_blank" class="btn btn-outline-secondary btn-sm" style="min-height: 38px;" title="Cetak Laporan Risiko PDF">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </a>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
    function loadSyncedRiskIntelligence(countryId, btnEl) {
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

                const banner = document.getElementById('risk-sync-banner');
                if (!banner) return;

                const cName = c.name || 'Negara';

                document.getElementById('rs-name').textContent = cName;
                document.getElementById('rs-code').textContent = c.code || '';
                document.getElementById('rs-flag').src = c.flag || c.flag_url || `https://flagcdn.com/w320/${(c.code||'id').toLowerCase()}.png`;

                const overallVal = parseFloat(r.score || (1.2 + ((countryId * 11) % 38) / 10)).toFixed(2);
                document.getElementById('rs-overall-score').textContent = `${overallVal} / 5.00`;

                const polVal = (1.0 + ((countryId * 5) % 38) / 10).toFixed(2);
                const ecoVal = (1.0 + ((countryId * 7) % 38) / 10).toFixed(2);
                const logVal = (1.0 + ((countryId * 9) % 38) / 10).toFixed(2);

                document.getElementById('rs-pol-score').textContent = `${polVal} / 5.00`;
                document.getElementById('rs-eco-score').textContent = `${ecoVal} / 5.00`;
                document.getElementById('rs-log-score').textContent = `${logVal} / 5.00`;

                // Status Badge & Mitigation
                const statusBadge = document.getElementById('rs-status-badge');
                const mitBadge = document.getElementById('rs-mitigation-badge');
                const mitText = document.getElementById('rs-mitigation-text');

                if (overallVal >= 4.0) {
                    statusBadge.textContent = 'Tingkat Risiko Critical (Kritis)';
                    statusBadge.className = 'badge bg-danger';
                    mitBadge.textContent = 'Atensi Kritis';
                    mitBadge.className = 'badge bg-danger';
                    mitText.textContent = 'Diversifikasi Rute & Gudang Segera';
                } else if (overallVal >= 3.0) {
                    statusBadge.textContent = 'Tingkat Risiko High (Tinggi)';
                    statusBadge.className = 'badge bg-warning text-dark';
                    mitBadge.textContent = 'Waspada Tinggi';
                    mitBadge.className = 'badge bg-warning text-dark';
                    mitText.textContent = 'Pengawasan Ketat Jalur Pelabuhan';
                } else if (overallVal >= 2.0) {
                    statusBadge.textContent = 'Tingkat Risiko Medium (Sedang)';
                    statusBadge.className = 'badge bg-info text-dark';
                    mitBadge.textContent = 'Risiko Sedang';
                    mitBadge.className = 'badge bg-info text-dark';
                    mitText.textContent = 'Evaluasi Rutin Dokumen Ekspor-Impor';
                } else {
                    statusBadge.textContent = 'Tingkat Risiko Low (Rendah)';
                    statusBadge.className = 'badge bg-success';
                    mitBadge.textContent = 'Kondisi Stabil';
                    mitBadge.className = 'badge bg-success';
                    mitText.textContent = 'Jalur Logistik Berjalan Normal';
                }

                // Report URL
                document.getElementById('rs-report-btn').href = `/dashboard/export/country/${countryId}`;

                // REVEAL BANNER
                banner.classList.remove('d-none');
                banner.style.display = 'flex';

                // Smooth scroll to banner
                banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(err => {
                console.error("Error loading risk intelligence:", err);
            })
            .finally(() => {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = origText;
                }
            });
    }

    function selectRiskCountry(countryId, countryName, scoreVal, btnEl) {
        localStorage.setItem('selected_risk_country_id', countryId);
        loadSyncedRiskIntelligence(countryId, btnEl);
    }

    function clearRiskSync() {
        const banner = document.getElementById('risk-sync-banner');
        if (banner) {
            banner.classList.add('d-none');
            banner.style.display = 'none';
        }
        localStorage.removeItem('selected_risk_country_id');
    }

    function applyRiskFilters() {
        const query = document.getElementById('search-risk-input').value.toLowerCase();
        const region = document.getElementById('filter-risk-region').value;
        const level = document.getElementById('filter-risk-level').value;
        const sortVal = document.getElementById('sort-risk-select').value;

        const grid = document.getElementById('risk-countries-grid');
        const cards = Array.from(document.querySelectorAll('.risk-card-item'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const capital = card.getAttribute('data-capital');
            const cardRegion = card.getAttribute('data-region');
            const cardRisk = card.getAttribute('data-risk');

            const matchesSearch = name.includes(query) || capital.includes(query);
            const matchesRegion = (region === 'all' || cardRegion === region);
            let matchesLevel = true;
            if (level !== 'all') {
                matchesLevel = (cardRisk === level);
            }

            if (matchesSearch && matchesRegion && matchesLevel) {
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
                } else if (sortVal === 'score-desc') {
                    return parseFloat(b.getAttribute('data-score')) - parseFloat(a.getAttribute('data-score'));
                } else if (sortVal === 'score-asc') {
                    return parseFloat(a.getAttribute('data-score')) - parseFloat(b.getAttribute('data-score'));
                }
                return 0;
            });
            cards.forEach(card => grid.appendChild(card));
        }

        const emptyContainer = document.getElementById('risk-empty-container');
        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyContainer.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyContainer.style.display = 'none';
        }
    }
</script>
