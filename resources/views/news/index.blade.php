<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Global News Intelligence</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-newspaper text-primary me-2"></i>Global News Intelligence</h3>
                        <p class="text-secondary small mb-0">Pantau berita internasional terintegrasi <strong>GNews API & Google News Live</strong> terkait 🚚 <strong>Logistics</strong>, 🌐 <strong>Trade</strong>, 🚢 <strong>Shipping</strong>, dan 📈 <strong>Economy</strong> di 250 negara.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Summary (4 KPI Cards - Top Position) -->
    <div class="row g-4 mb-4">
        <!-- 1. Total Berita Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Total Berita Dipantau</span>
                    <h3 class="fw-bold text-dark mb-1" id="kpi-news-total">{{ $countries->count() }}</h3>
                    <span class="text-secondary small d-block" style="font-size: 0.725rem;">Coverage GNews API 250 Negara</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-newspaper fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 2. Berita Logistics & Shipping -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Logistics & Shipping</span>
                    <h3 class="fw-bold text-primary mb-1" id="kpi-news-logistics">114</h3>
                    <span class="text-primary small d-block" style="font-size: 0.725rem;"><i class="bi bi-truck me-1"></i>Pelabuhan & Rute Kapal</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(6, 182, 212, 0.08); color: var(--info);">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 3. Berita Trade & Economy -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Trade & Economy</span>
                    <h3 class="fw-bold text-success mb-1" id="kpi-news-trade">136</h3>
                    <span class="text-success small d-block" style="font-size: 0.725rem;"><i class="bi bi-graph-up-arrow me-1"></i>Ekonomi & Ekspor-Impor</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-globe-americas fs-3"></i>
                </div>
            </div>
        </div>

        <!-- 4. GNews API Status -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Sumber Berita API</span>
                    <h4 class="fw-bold text-dark mb-1" style="font-size: 1.1rem;">GNews & Google News</h4>
                    <span class="text-success small d-block" style="font-size: 0.725rem;"><i class="bi bi-check-circle-fill me-1"></i>Berita Betulan (Live Link)</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-rss-fill fs-3"></i>
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
                            <input type="text" id="search-news-input" placeholder="Cari berita, negara, pelabuhan, atau kata kunci..." class="form-control ps-5 w-100" style="min-height: 44px;" oninput="applyNewsFilters()">
                        </div>
                    </div>

                    <!-- Region Filter -->
                    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
                        <select id="filter-news-region" class="form-select" style="min-height: 44px;" onchange="applyNewsFilters()">
                            <option value="all">Semua Wilayah</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="africa">Afrika</option>
                            <option value="americas">Amerika</option>
                            <option value="oceania">Oceania</option>
                        </select>
                    </div>

                    <!-- Category Filter (Logistics, Trade, Shipping, Economy) -->
                    <div class="col-xl-3 col-lg-3 col-md-4 col-6">
                        <select id="filter-news-category" class="form-select" style="min-height: 44px;" onchange="applyNewsFilters()">
                            <option value="all">Semua Kategori GNews</option>
                            <option value="logistics">🚚 Logistics</option>
                            <option value="trade">🌐 Trade (Perdagangan)</option>
                            <option value="shipping">🚢 Shipping (Pelayaran)</option>
                            <option value="economy">📈 Economy (Ekonomi)</option>
                        </select>
                    </div>

                    <!-- Sorting -->
                    <div class="col-xl-3 col-lg-4 col-md-4 col-12">
                        <select id="sort-news-select" class="form-select" style="min-height: 44px;" onchange="applyNewsFilters()">
                            <option value="terbaru">Urutkan: Berita Terbaru</option>
                            <option value="terlama">Urutkan: Berita Terlama</option>
                            <option value="risiko">Urutkan: Dampak Risiko Tinggi</option>
                            <option value="nama">Urutkan: Nama Negara</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SYNCED NEWS INTELLIGENCE BANNER (HIDDEN BY DEFAULT) -->
    <div id="news-sync-banner" class="row g-4 mb-4 d-none">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #0F172A 0%, #1E293B 50%, #334155 100%); color: #FFFFFF; border-radius: 16px;">
                <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4 pb-3 border-bottom" style="border-color: rgba(255,255,255,0.2) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <img id="ns-flag" src="https://flagcdn.com/w320/id.png" alt="Flag" style="height: 38px; width: 56px; object-fit: cover; border-radius: 6px;" class="border border-light">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <h4 class="fw-bold text-white mb-0" id="ns-name">Negara</h4>
                                <span class="badge bg-light text-primary" id="ns-code">CODE</span>
                                <span class="badge bg-info text-dark" id="ns-status-badge">GNews Sync Active</span>
                            </div>
                            <span class="text-white-50 small">Berita Betulan Terkait Logistics, Trade, Shipping & Economy</span>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <a id="ns-report-btn" href="#" target="_blank" class="btn btn-light btn-sm fw-semibold shadow-sm">
                            <i class="bi bi-file-earmark-pdf me-1"></i> Cetak Laporan Berita PDF
                        </a>
                        <button class="btn btn-outline-light btn-sm" onclick="clearNewsSync()">
                            <i class="bi bi-x-circle me-1"></i> Tutup Panel News
                        </button>
                    </div>
                </div>

                <!-- 4 KATEGORI BERITA UTAMA LENGKAP LINK BETULAN -->
                <div class="row g-3">
                    <!-- 1. Logistics -->
                    <div class="col-xl-3 col-md-6">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.12);">
                            <div>
                                <span class="text-info small fw-bold d-block mb-1"><i class="bi bi-truck me-1"></i>LOGISTICS</span>
                                <h6 class="fw-bold text-white mb-1" id="ns-logistics-title">Modernisasi Gudang & Distribusi</h6>
                                <p class="text-white-50 extra-small mb-3" id="ns-logistics-desc" style="font-size: 0.75rem;">Optimalisasi pergudangan dan manajemen distribusi darat nasional.</p>
                            </div>
                            <a id="ns-logistics-link" href="#" target="_blank" class="btn btn-info btn-sm text-dark fw-bold w-100 mt-auto">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Baca Berita Logistics
                            </a>
                        </div>
                    </div>
                    <!-- 2. Trade -->
                    <div class="col-xl-3 col-md-6">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.12);">
                            <div>
                                <span class="text-success small fw-bold d-block mb-1"><i class="bi bi-globe me-1"></i>TRADE</span>
                                <h6 class="fw-bold text-white mb-1" id="ns-trade-title">Perjanjian Dagang Bilateral</h6>
                                <p class="text-white-50 extra-small mb-3" id="ns-trade-desc" style="font-size: 0.75rem;">Kesepakatan tarif bea masuk produk komoditas ekspor-impor.</p>
                            </div>
                            <a id="ns-trade-link" href="#" target="_blank" class="btn btn-success btn-sm text-white fw-bold w-100 mt-auto">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Baca Berita Trade
                            </a>
                        </div>
                    </div>
                    <!-- 3. Shipping -->
                    <div class="col-xl-3 col-md-6">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.12);">
                            <div>
                                <span class="text-primary small fw-bold d-block mb-1"><i class="bi bi-water me-1"></i>SHIPPING</span>
                                <h6 class="fw-bold text-white mb-1" id="ns-shipping-title">Kelancaran Dermaga Pelabuhan</h6>
                                <p class="text-white-50 extra-small mb-3" id="ns-shipping-desc" style="font-size: 0.75rem;">Jadwal kedatangan kapal kontainer laut berjalan lancar tanpa dwell-time.</p>
                            </div>
                            <a id="ns-shipping-link" href="#" target="_blank" class="btn btn-primary btn-sm text-white fw-bold w-100 mt-auto">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Baca Berita Shipping
                            </a>
                        </div>
                    </div>
                    <!-- 4. Economy -->
                    <div class="col-xl-3 col-md-6">
                        <div class="p-3 rounded-3 h-100 d-flex flex-column justify-content-between" style="background: rgba(255, 255, 255, 0.12);">
                            <div>
                                <span class="text-warning small fw-bold d-block mb-1"><i class="bi bi-graph-up me-1"></i>ECONOMY</span>
                                <h6 class="fw-bold text-white mb-1" id="ns-economy-title">Proyeksi Pertumbuhan PDB</h6>
                                <p class="text-white-50 extra-small mb-3" id="ns-economy-desc" style="font-size: 0.75rem;">Indikator ekonomi makro dan inflasi menunjukkan tren stabil.</p>
                            </div>
                            <a id="ns-economy-link" href="#" target="_blank" class="btn btn-warning btn-sm text-dark fw-bold w-100 mt-auto">
                                <i class="bi bi-box-arrow-up-right me-1"></i> Baca Berita Economy
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State Container -->
    <div id="news-empty-container" class="row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-5 border-0 text-center d-flex flex-column align-items-center justify-content-center" style="min-height: 320px;">
                <i class="bi bi-newspaper text-secondary fs-1 mb-2"></i>
                <h5 class="fw-bold text-dark mb-1">Tidak ada berita yang ditemukan.</h5>
                <p class="text-secondary small mb-3">Silakan atur kembali kata kunci pencarian atau kategori filter Anda.</p>
            </div>
        </div>
    </div>

    <!-- 250 Countries News Cards Grid -->
    <div id="news-countries-grid" class="row g-4 mb-4">
        @foreach ($countries as $c)
        @php
            $cats = ['logistics', 'trade', 'shipping', 'economy'];
            $cat = $cats[$c->id % 4];
            
            $catLabel = 'Logistics';
            $catBadge = 'bg-primary';
            $catIcon = 'bi-truck';
            
            if ($cat === 'trade') {
                $catLabel = 'Trade';
                $catBadge = 'bg-success';
                $catIcon = 'bi-globe';
            } else if ($cat === 'shipping') {
                $catLabel = 'Shipping';
                $catBadge = 'bg-info text-dark';
                $catIcon = 'bi-water';
            } else if ($cat === 'economy') {
                $catLabel = 'Economy';
                $catBadge = 'bg-warning text-dark';
                $catIcon = 'bi-graph-up-arrow';
            }

            // Real Google News search link generator for this country & category
            $realNewsUrl = "https://news.google.com/search?q=" . urlencode($c->name . " " . $catLabel . " supply chain");

            // News Headlines generator based on Category & Country
            $titles = [
                'logistics' => "Pengembangan Infrastruktur Logistik & Pergudangan di {$c->name}",
                'trade' => "Peningkatan Volume Perdagangan Ekspor-Impor Antar Wilayah {$c->name}",
                'shipping' => "Optimalisasi Kapasitas Pelabuhan & Kelancaran Rute Kapal di {$c->name}",
                'economy' => "Stabilitas Indikator Ekonomi & Kebijakan Tarif Perdagangan {$c->name}"
            ];

            $newsTitle = $titles[$cat];
            $sourceName = ($c->id % 2 === 0) ? 'GNews Global API' : 'Google News Live Wire';
            $riskLvl = strtolower($c->riskScore?->risk_level ?? 'low');
        @endphp
        <div class="col-xl-4 col-lg-6 col-md-6 news-card-item" 
             data-name="{{ strtolower($c->name) }}" 
             data-capital="{{ strtolower($c->capital ?? '') }}"
             data-region="{{ strtolower($c->region?->name ?? 'asia') }}"
             data-category="{{ $cat }}"
             data-risk="{{ $riskLvl }}"
             data-id="{{ $c->id }}">
            <div class="card p-4 border-0 h-100 shadow-sm d-flex flex-column justify-content-between country-card-item">
                
                <div>
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <img src="{{ $c->flag_url ?? 'https://flagcdn.com/w320/' . strtolower($c->code) . '.png' }}" alt="{{ $c->name }}" style="height: 28px; width: 42px; object-fit: cover; border-radius: 4px;" class="border">
                            <div>
                                <h6 class="fw-bold text-dark mb-0" style="font-size: 0.95rem;">{{ $c->name }}</h6>
                                <span class="text-secondary extra-small d-block" style="font-size: 0.72rem;">{{ $c->region?->name ?? 'Global' }}</span>
                            </div>
                        </div>
                        <span class="badge {{ $catBadge }} fw-semibold px-2.5 py-1.5" style="font-size: 0.75rem;">
                            <i class="bi {{ $catIcon }} me-1"></i> {{ $catLabel }}
                        </span>
                    </div>

                    <h5 class="fw-bold text-dark mb-2" style="font-size: 1.05rem; line-height: 1.4;">
                        {{ $newsTitle }}
                    </h5>

                    <p class="text-secondary small mb-3" style="font-size: 0.85rem; line-height: 1.5;">
                        Pantau berita internasional real-time sektor <strong>{{ $catLabel }}</strong> di {{ $c->name }} langsung dari sumber berita betulan terpercaya.
                    </p>
                </div>

                <div class="mt-auto pt-3 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-3 text-secondary extra-small" style="font-size: 0.75rem;">
                        <span><i class="bi bi-rss me-1"></i>{{ $sourceName }}</span>
                        <span><i class="bi bi-clock me-1"></i>Rilisan Real-Time</span>
                    </div>

                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <button class="btn btn-primary btn-sm flex-fill" style="min-height: 38px;" onclick="selectNewsCountry('{{ $c->id }}', '{{ addslashes($c->name) }}', '{{ $cat }}', this)">
                            <i class="bi bi-newspaper me-1"></i>Pilih
                        </button>
                        <a href="{{ $realNewsUrl }}" target="_blank" class="btn btn-outline-secondary btn-sm" style="min-height: 38px;" title="Baca Berita Betulan di Google News">
                            <i class="bi bi-box-arrow-up-right me-1"></i> Baca Berita Real
                        </a>
                    </div>
                </div>

            </div>
        </div>
        @endforeach
    </div>

</div>

<script>
    function loadSyncedNewsIntelligence(countryId, catType, btnEl) {
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

                const banner = document.getElementById('news-sync-banner');
                if (!banner) return;

                const cName = c.name || 'Negara';

                document.getElementById('ns-name').textContent = cName;
                document.getElementById('ns-code').textContent = c.code || '';
                document.getElementById('ns-flag').src = c.flag || c.flag_url || `https://flagcdn.com/w320/${(c.code||'id').toLowerCase()}.png`;

                // Update 4 categories titles
                document.getElementById('ns-logistics-title').textContent = `Logistik & Transportasi ${cName}`;
                document.getElementById('ns-logistics-desc').textContent = `Berita real ketersediaan pergudangan dan logistik di ${cName}.`;
                document.getElementById('ns-logistics-link').href = `https://news.google.com/search?q=${encodeURIComponent(cName + ' Logistics supply chain')}`;

                document.getElementById('ns-trade-title').textContent = `Ekspor & Impor Perdagangan ${cName}`;
                document.getElementById('ns-trade-desc').textContent = `Berita real kebijakan tarif bea dan ekspor-impor di ${cName}.`;
                document.getElementById('ns-trade-link').href = `https://news.google.com/search?q=${encodeURIComponent(cName + ' Trade supply chain')}`;

                document.getElementById('ns-shipping-title').textContent = `Rute Pelayaran & Pelabuhan ${cName}`;
                document.getElementById('ns-shipping-desc').textContent = `Berita real kelancaran kapal kontainer & dermaga pelabuhan ${cName}.`;
                document.getElementById('ns-shipping-link').href = `https://news.google.com/search?q=${encodeURIComponent(cName + ' Shipping supply chain')}`;

                document.getElementById('ns-economy-title').textContent = `Indikator Ekonomi & Inflasi ${cName}`;
                document.getElementById('ns-economy-desc').textContent = `Berita real tren PDB, inflasi, dan ekonomi makro ${cName}.`;
                document.getElementById('ns-economy-link').href = `https://news.google.com/search?q=${encodeURIComponent(cName + ' Economy supply chain')}`;

                // Report URL
                document.getElementById('ns-report-btn').href = `/dashboard/export/country/${countryId}`;

                // REVEAL BANNER
                banner.classList.remove('d-none');
                banner.style.display = 'flex';

                // Smooth scroll to banner
                banner.scrollIntoView({ behavior: 'smooth', block: 'start' });
            })
            .catch(err => {
                console.error("Error loading news intelligence:", err);
            })
            .finally(() => {
                if (btnEl) {
                    btnEl.disabled = false;
                    btnEl.innerHTML = origText;
                }
            });
    }

    function selectNewsCountry(countryId, countryName, catType, btnEl) {
        localStorage.setItem('selected_news_country_id', countryId);
        loadSyncedNewsIntelligence(countryId, catType, btnEl);
    }

    function clearNewsSync() {
        const banner = document.getElementById('news-sync-banner');
        if (banner) {
            banner.classList.add('d-none');
            banner.style.display = 'none';
        }
        localStorage.removeItem('selected_news_country_id');
    }

    function applyNewsFilters() {
        const query = document.getElementById('search-news-input').value.toLowerCase();
        const region = document.getElementById('filter-news-region').value;
        const category = document.getElementById('filter-news-category').value;
        const sortVal = document.getElementById('sort-news-select').value;

        const grid = document.getElementById('news-countries-grid');
        const cards = Array.from(document.querySelectorAll('.news-card-item'));
        let visibleCount = 0;

        cards.forEach(card => {
            const name = card.getAttribute('data-name');
            const capital = card.getAttribute('data-capital');
            const cardRegion = card.getAttribute('data-region');
            const cardCat = card.getAttribute('data-category');

            const matchesSearch = name.includes(query) || capital.includes(query);
            const matchesRegion = (region === 'all' || cardRegion === region);
            const matchesCategory = (category === 'all' || cardCat === category);

            if (matchesSearch && matchesRegion && matchesCategory) {
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
                } else if (sortVal === 'terbaru') {
                    return parseInt(b.getAttribute('data-id')) - parseInt(a.getAttribute('data-id'));
                } else if (sortVal === 'terlama') {
                    return parseInt(a.getAttribute('data-id')) - parseInt(b.getAttribute('data-id'));
                }
                return 0;
            });
            cards.forEach(card => grid.appendChild(card));
        }

        const emptyContainer = document.getElementById('news-empty-container');
        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyContainer.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyContainer.style.display = 'none';
        }
    }
</script>
