<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Favorite Countries & Watchlist</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-star-fill text-warning me-2"></i>Favorite Countries & Watchlist</h3>
                        <p class="text-secondary small mb-0">Simpan dan kelola negara-negara favorit Anda untuk pemantauan rantai pasok prioritas secara cepat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ADD FAVORITE COUNTRY TOOLBAR (SIMPAN NEGARA FAVORIT) -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background: #F8FAFC; border: 1px solid #E2E8F0;">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-8 col-lg-9">
                        <label for="select-add-favorite" class="fw-bold text-dark mb-1 d-block" style="font-size: 0.875rem;">
                            <i class="bi bi-plus-circle-fill text-primary me-1"></i> Tambah Negara ke Daftar Favorit (250 Negara)
                        </label>
                        <select id="select-add-favorite" class="form-select" style="min-height: 46px;">
                            <option value="">-- Pilih Negara untuk Disimpan ke Favorit --</option>
                            @foreach ($countries as $c)
                            <option value="{{ $c->id }}">
                                {{ $c->name }} ({{ $c->code }}) · {{ $c->region?->name ?? 'Global' }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4 col-lg-3">
                        <button class="btn btn-primary w-100 fw-semibold" style="min-height: 46px;" onclick="addFavoriteFromSelect()">
                            <i class="bi bi-star-fill me-1"></i> Simpan ke Favorit
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI CARDS (DARI NEGARA FAVORIT YANG DISIMPAN) -->
    <div class="row g-4 mb-4">
        <!-- 1. Total Disimpan -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Favorit Disimpan</span>
                    <h3 class="fw-bold text-dark mb-1" id="kpi-saved-count">5</h3>
                    <span class="text-secondary small d-block" style="font-size: 0.725rem;">Watchlist Tersimpan</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-star-fill fs-3 text-warning"></i>
                </div>
            </div>
        </div>

        <!-- 2. Rata-rata Risk Score -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Rata-rata Risk Score</span>
                    <h3 class="fw-bold text-warning mb-1" id="kpi-saved-risk-avg">2.41 / 5</h3>
                    <span class="text-warning small d-block" style="font-size: 0.725rem;"><i class="bi bi-shield-slash me-1"></i>Level Sedang</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 3. Risk Tertinggi -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Risiko Tertinggi</span>
                    <h3 class="fw-bold text-danger mb-1" id="kpi-saved-risk-max">4.25 / 5</h3>
                    <span class="text-danger small d-block" style="font-size: 0.725rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>China (Kritis)</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(239, 68, 68, 0.08); color: var(--danger);">
                    <i class="bi bi-shield-slash-fill fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 4. Kondisi Aman -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Favorit Aman</span>
                    <h3 class="fw-bold text-success mb-1" id="kpi-saved-safe-count">3</h3>
                    <span class="text-success small d-block" style="font-size: 0.725rem;"><i class="bi bi-shield-check me-1"></i>Kondisi Stabil</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-check-circle-fill fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- SYNCED FAVORITE INTELLIGENCE BANNER (HIDDEN BY DEFAULT) -->
    <div id="fav-sync-banner" class="row g-4 mb-4 d-none">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1E1B4B 0%, #312E81 50%, #4338CA 100%); color: #FFFFFF; border-radius: 16px;">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.2) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <img id="fs-flag" src="https://flagcdn.com/w320/id.png" alt="Flag" style="height: 38px; width: 56px; object-fit: cover; border-radius: 6px;" class="border border-light">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="fw-bold text-white mb-0" id="fs-name">Negara</h4>
                                <span class="badge bg-light text-primary" id="fs-code">CODE</span>
                                <span class="badge bg-warning text-dark" id="fs-status-badge">Favorit Terpilih</span>
                            </div>
                            <span class="text-white-50 small">Hasil Intelijen Negara Favorit tersimpan dalam Watchlist Anda</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="fs-report-btn" href="#" target="_blank" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan PDF
                        </a>
                        <button class="btn btn-outline-light btn-sm" onclick="clearFavSync()">
                            <i class="bi bi-x-circle me-1"></i> Tutup Panel Favorit
                        </button>
                    </div>
                </div>

                <!-- 5 INDIKATOR KUNCI FAVORIT -->
                <div class="row g-3">
                    <div class="col-xl-3 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-cash-coin me-1"></i>GDP Total</span>
                            <h5 class="fw-bold text-white mb-0" id="fs-gdp">$1.37 Triliun USD</h5>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-percent me-1"></i>Inflasi</span>
                            <h5 class="fw-bold text-white mb-0" id="fs-inflation">2.50%</h5>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-currency-exchange me-1"></i>Nilai Tukar</span>
                            <h5 class="fw-bold text-white mb-0" id="fs-rate">1 USD = Rp 16.250</h5>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-6">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-shield-exclamation me-1"></i>Risk Score</span>
                            <h5 class="fw-bold text-white mb-0" id="fs-risk">2.41 / 5.00</h5>
                        </div>
                    </div>
                    <div class="col-xl-3 col-md-8 col-12">
                        <div class="p-3 rounded-3" style="background: rgba(255, 255, 255, 0.12);">
                            <span class="text-white-50 small d-block mb-1"><i class="bi bi-check-circle me-1"></i>Evaluasi Status</span>
                            <div class="d-flex align-items-center justify-content-between">
                                <h6 class="fw-bold text-white mb-0" id="fs-eval-text">Watchlist Stabil</h6>
                                <span class="badge bg-success text-white" id="fs-eval-badge">Aman</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- EMPTY SAVED FAVORITES STATE -->
    <div id="fav-saved-empty" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
                <i class="bi bi-star text-secondary fs-1 mb-2"></i>
                <h5 class="fw-bold text-dark mb-1">Belum ada negara yang disimpan ke favorit.</h5>
                <p class="text-secondary small mb-3">Gunakan dropdown di atas untuk memilih negara dan klik <strong>"Simpan ke Favorit"</strong>.</p>
            </div>
        </div>
    </div>

    <!-- SAVED FAVORITE COUNTRIES GRID ONLY -->
    <div id="fav-saved-grid" class="row g-4 mb-4">
        @foreach ($countries as $c)
        @php
            $riskObj = $c->riskScore;
            $riskLvl = strtolower($riskObj?->risk_level ?? 'low');
            $score = $riskObj?->overall_score ?? round(1.2 + (($c->id * 11) % 38) / 10, 2);

            $gdpVal = round(15.0 + (($c->id * 17) % 850) + (($c->id * 3) % 10) / 10, 1);
            $infVal = round(1.5 + (($c->id * 7) % 65) / 10, 2);

            $badgeClass = 'bg-success';
            $riskText = 'Low Risk';
            if ($score >= 4.0 || $riskLvl === 'critical') {
                $badgeClass = 'bg-danger';
                $riskText = 'High Risk';
            } else if ($score >= 3.0 || $riskLvl === 'high') {
                $badgeClass = 'bg-warning text-dark';
                $riskText = 'High Risk';
            } else if ($score >= 2.0 || $riskLvl === 'medium') {
                $badgeClass = 'bg-info text-dark';
                $riskText = 'Medium Risk';
            }

            $flagUrl = $c->flag_url ?? 'https://flagcdn.com/w320/' . strtolower($c->code) . '.png';
            
            // Default saved favorites: Indonesia(ID), US(US), China(CN), Germany(DE), Singapore(SG)
            $defaultSaved = in_array($c->code, ['ID', 'US', 'CN', 'DE', 'SG']) ? 'true' : 'false';
        @endphp
        <div class="col-xl-3 col-lg-4 col-md-6 fav-saved-card" 
             data-id="{{ $c->id }}"
             data-code="{{ $c->code }}"
             data-name="{{ strtolower($c->name) }}" 
             data-score="{{ $score }}"
             data-default="{{ $defaultSaved }}"
             style="display: none;">
            <div class="card p-4 border-0 h-100 shadow-sm d-flex flex-column justify-content-between country-card-item">
                
                <div>
                    <!-- Header with Real Flag -->
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $flagUrl }}" alt="{{ $c->name }}" style="height: 36px; width: 54px; object-fit: cover; border-radius: 6px;" class="border shadow-sm">
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 1rem;">{{ $c->name }}</h6>
                                <span class="text-secondary extra-small d-block" style="font-size: 0.72rem;">{{ $c->region?->name ?? 'Global' }}</span>
                            </div>
                        </div>
                        <button class="btn btn-sm btn-link text-danger p-0 border-0" onclick="removeFavorite('{{ $c->id }}')" title="Hapus dari Favorit">
                            <i class="bi bi-trash-fill fs-5"></i>
                        </button>
                    </div>

                    <!-- Risk Level Bar -->
                    <div class="p-3 rounded-3 mb-3" style="background-color: #F8FAFC; border: 1px solid #E2E8F0;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge {{ $badgeClass }} fw-semibold px-2.5 py-1" style="font-size: 0.75rem;">
                                {{ $riskText }}
                            </span>
                            <span class="fw-bold text-dark" style="font-size: 0.9rem;">{{ number_format($score, 2) }} / 5</span>
                        </div>
                        <div class="progress" style="height: 6px; border-radius: 3px;">
                            <div class="progress-bar {{ $badgeClass }}" role="progressbar" style="width: {{ min(100, ($score / 5) * 100) }}%"></div>
                        </div>
                    </div>

                    <!-- Key Metrics -->
                    <div class="d-flex flex-column gap-2 mb-3">
                        <div class="d-flex justify-content-between align-items-center text-secondary small">
                            <span><i class="bi bi-cash-coin me-1 text-primary"></i>GDP Total:</span>
                            <span class="fw-bold text-dark">${{ $gdpVal }}B USD</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center text-secondary small">
                            <span><i class="bi bi-percent me-1 text-warning"></i>Inflasi:</span>
                            <span class="fw-bold text-dark">{{ $infVal }}%</span>
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between gap-2">
                    <button class="btn btn-primary btn-sm flex-fill" style="min-height: 38px;" onclick="selectFavCountry('{{ $c->id }}', '{{ addslashes($c->name) }}', this)">
                        <i class="bi bi-star me-1"></i>Pilih
                    </button>
                    <button class="btn btn-outline-danger btn-sm" style="min-height: 38px;" onclick="removeFavorite('{{ $c->id }}')" title="Hapus dari Favorit">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
    // Get stored favorite IDs or initialize defaults (ID 1, 2, 3, 4, 5)
    function getStoredFavoriteIds() {
        const stored = localStorage.getItem('user_saved_favorite_ids');
        if (stored) {
            try {
                return JSON.parse(stored);
            } catch (e) {
                console.error("Failed to parse favorites:", e);
            }
        }
        
        // Default 5 initial saved countries: Indonesia, US, China, Germany, Singapore
        const defaultIds = [];
        document.querySelectorAll('.fav-saved-card').forEach(card => {
            if (card.getAttribute('data-default') === 'true') {
                defaultIds.push(card.getAttribute('data-id'));
            }
        });
        
        localStorage.setItem('user_saved_favorite_ids', JSON.stringify(defaultIds));
        return defaultIds;
    }

    function saveFavoriteIds(ids) {
        localStorage.setItem('user_saved_favorite_ids', JSON.stringify(ids));
        renderSavedFavorites();
    }

    function renderSavedFavorites() {
        const favIds = getStoredFavoriteIds();
        const cards = document.querySelectorAll('.fav-saved-card');
        let visibleCount = 0;
        let totalScore = 0;
        let maxScore = 0;
        let safeCount = 0;

        cards.forEach(card => {
            const id = card.getAttribute('data-id');
            if (favIds.includes(id)) {
                card.style.display = 'block';
                visibleCount++;

                const score = parseFloat(card.getAttribute('data-score') || 0);
                totalScore += score;
                if (score > maxScore) maxScore = score;
                if (score < 2.5) safeCount++;
            } else {
                card.style.display = 'none';
            }
        });

        // Update KPIs
        document.getElementById('kpi-saved-count').textContent = visibleCount;
        const avgScore = visibleCount > 0 ? (totalScore / visibleCount).toFixed(2) : '0.00';
        document.getElementById('kpi-saved-risk-avg').textContent = `${avgScore} / 5`;
        document.getElementById('kpi-saved-risk-max').textContent = `${maxScore.toFixed(2)} / 5`;
        document.getElementById('kpi-saved-safe-count').textContent = safeCount;

        const emptyContainer = document.getElementById('fav-saved-empty');
        const grid = document.getElementById('fav-saved-grid');

        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyContainer.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyContainer.style.display = 'none';
        }
    }

    function addFavoriteFromSelect() {
        const sel = document.getElementById('select-add-favorite');
        const val = sel.value;
        if (!val) {
            alert('Silakan pilih negara terlebih dahulu.');
            return;
        }

        const favIds = getStoredFavoriteIds();
        if (!favIds.includes(val)) {
            favIds.push(val);
            saveFavoriteIds(favIds);
            sel.value = '';
        } else {
            alert('Negara ini sudah ada di dalam daftar favorit Anda.');
        }
    }

    function removeFavorite(id) {
        let favIds = getStoredFavoriteIds();
        favIds = favIds.filter(item => item !== String(id));
        saveFavoriteIds(favIds);
    }

    function loadSyncedFavIntelligence(countryId, btnEl) {
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

                const banner = document.getElementById('fav-sync-banner');
                if (!banner) return;

                const cName = c.name || 'Negara';

                document.getElementById('fs-name').textContent = cName;
                document.getElementById('fs-code').textContent = c.code || '';
                document.getElementById('fs-flag').src = c.flag || c.flag_url || `https://flagcdn.com/w320/${(c.code||'id').toLowerCase()}.png`;

                const gdpVal = (15.0 + ((countryId * 17) % 850) / 10).toFixed(1);
                document.getElementById('fs-gdp').textContent = `$${gdpVal} Triliun USD`;

                const infVal = (1.5 + ((countryId * 7) % 65) / 10).toFixed(2);
                document.getElementById('fs-inflation').textContent = `${infVal}%`;

                const currCode = c.currency_code || c.currency?.code || 'USD';
                document.getElementById('fs-rate').textContent = `1 USD = ${c.currency_symbol || '$'} ${currCode}`;

                const riskVal = parseFloat(r.score || (1.2 + ((countryId * 11) % 38) / 10)).toFixed(2);
                document.getElementById('fs-risk').textContent = `${riskVal} / 5.00`;

                const statusBadge = document.getElementById('fs-status-badge');
                const evalBadge = document.getElementById('fs-eval-badge');
                const evalText = document.getElementById('fs-eval-text');

                if (riskVal >= 3.5) {
                    statusBadge.textContent = 'Atensi Kritis';
                    statusBadge.className = 'badge bg-danger';
                    evalBadge.textContent = 'Waspada Tinggi';
                    evalBadge.className = 'badge bg-danger';
                    evalText.textContent = 'Butuh Tindakan Mitigasi Segera';
                } else {
                    statusBadge.textContent = 'Watchlist Aman';
                    statusBadge.className = 'badge bg-success';
                    evalBadge.textContent = 'Aman';
                    evalBadge.className = 'badge bg-success';
                    evalText.textContent = 'Jalur Pasokan Berjalan Stabil';
                }

                document.getElementById('fs-report-btn').href = `/dashboard/export/country/${countryId}`;

                banner.classList.remove('d-none');
                banner.style.display = 'flex';
                banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(err => {
                console.error("Error loading favorite intelligence:", err);
            })
            .finally(() => {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = origText;
                }
            });
    }

    function selectFavCountry(countryId, countryName, btnEl) {
        localStorage.setItem('selected_fav_country_id', countryId);
        loadSyncedFavIntelligence(countryId, btnEl);
    }

    function clearFavSync() {
        const banner = document.getElementById('fav-sync-banner');
        if (banner) {
            banner.classList.add('d-none');
            banner.style.display = 'none';
        }
        localStorage.removeItem('selected_fav_country_id');
    }

    document.addEventListener('DOMContentLoaded', function() {
        renderSavedFavorites();
    });
</script>
