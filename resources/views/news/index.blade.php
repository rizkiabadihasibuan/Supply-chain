@extends('layouts.user.app')

@section('title', 'Global News Intelligence - SupplyChain Platform')

@section('content')
<!-- Page Header Component -->
<x-page-header title="Global News Intelligence" subtitle="Pantau berita internasional yang berpotensi memengaruhi rantai pasok global secara cepat dan terstruktur." :breadcrumbs="['Global News Intelligence' => '#']">
    <x-slot name="actions">
        <button class="btn btn-primary" id="btn-sync-news" onclick="triggerNewsSync()" style="min-height: 44px;">
            <i class="bi bi-arrow-clockwise me-2"></i>Sinkronisasi Berita
        </button>
    </x-slot>
</x-page-header>

<!-- Summary KPI Cards (4 Cards) -->
<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <x-kpi-card title="Total Berita Hari Ini" value="12" description="Rilisan Media Terpantau" icon="bi-newspaper" type="primary" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-kpi-card title="Berita Risiko Tinggi" value="4" description="Hambatan Logistik Kritis" icon="bi-shield-exclamation" type="danger" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-kpi-card title="Berita Ekonomi" value="5" description="Tarif & Kebijakan Dagang" icon="bi-graph-up-arrow" type="warning" />
    </div>
    <div class="col-xl-3 col-md-6">
        <x-kpi-card title="Berita Logistik" value="3" description="Kondisi Port & Rute" icon="bi-truck" type="success" />
    </div>
</div>

<!-- Header Toolbar Component -->
<x-search-toolbar placeholder="Cari berita, negara, atau kata kunci..." searchId="search-news-input" oninput="applyNewsFilters()">
    <x-slot name="filters">
        <!-- Region Filter -->
        <div class="col-xl-2 col-lg-2 col-md-4 col-6">
            <select id="filter-news-region" class="form-select" style="min-height: 44px;" onchange="applyNewsFilters()">
                <option value="all">Semua Wilayah</option>
                <option value="asia">Asia</option>
                <option value="europe">Eropa</option>
                <option value="america">Amerika</option>
                <option value="africa">Afrika</option>
            </select>
        </div>
        <!-- Category Filter -->
        <div class="col-xl-2 col-lg-2 col-md-4 col-6">
            <select id="filter-news-category" class="form-select" style="min-height: 44px;" onchange="applyNewsFilters()">
                <option value="all">Semua Kategori</option>
                <option value="ekonomi">Ekonomi</option>
                <option value="politik">Politik</option>
                <option value="logistik">Logistik</option>
                <option value="pelabuhan">Pelabuhan</option>
                <option value="cuaca">Cuaca</option>
                <option value="energi">Energi</option>
                <option value="perdagangan">Perdagangan</option>
            </select>
        </div>
        <!-- Risk Filter -->
        <div class="col-xl-2 col-lg-3 col-md-4 col-6">
            <select id="filter-news-risk" class="form-select" style="min-height: 44px;" onchange="applyNewsFilters()">
                <option value="all">Semua Dampak</option>
                <option value="high">Kritis / Tinggi</option>
                <option value="medium">Sedang</option>
                <option value="low">Rendah</option>
            </select>
        </div>
        <!-- Date Picker Placeholder -->
        <div class="col-xl-2 col-lg-3 col-md-12 col-6">
            <input type="text" class="form-control" placeholder="Rentang Waktu..." style="min-height: 44px;" disabled title="Filter rentang waktu siap digunakan pada milestone berikutnya">
        </div>
    </x-slot>

    <x-slot name="simulations">
        <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateSkeletonLoading()">
            <i class="bi bi-hourglass-split me-2"></i>Simulasikan Loading
        </button>
        <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateEmptyState()">
            <i class="bi bi-x-circle me-2"></i>Simulasikan Data Kosong
        </button>
        <button class="btn btn-light btn-sm px-3" style="min-height: 38px; height: 38px;" onclick="simulateErrorState()">
            <i class="bi bi-exclamation-octagon me-2"></i>Simulasikan Error
        </button>
    </x-slot>
</x-search-toolbar>

<!-- Loading State Shimmer component -->
<div id="skeleton-container" style="display: none;">
    <x-loading-state type="card" count="4" height="240px" />
</div>

<!-- Empty State Component -->
<div id="empty-state-container" style="display: none;">
    <x-empty-state title="Belum ada berita tersedia." description="Tidak ada artikel media yang cocok dengan parameter saringan Anda." onclick="resetFilters()" />
</div>

<!-- Error State Component -->
<div id="error-state-container" style="display: none;">
    <x-error-state title="Gagal Menghubungkan Intelijen Berita." description="Stasiun RSS feed media internasional sedang sibuk. Silakan segarkan pemuatan." onclick="retryFromError()" />
</div>

<!-- Main Content Grid -->
<div id="main-content-grid" class="row g-4">
    <!-- Kolom Kiri (Featured, News Feed, Trending, Timeline) -->
    <div class="col-lg-8">
        <div class="d-flex flex-column gap-4">
            
            <!-- SECTION 1: Featured News -->
            <div id="featured-news-area">
                <x-featured-news-card 
                    title="Terusan Suez Mengalami Penurunan Arus Kargo Akibat Hambatan Teritorial" 
                    summary="Kepadatan penumpukan kapal kontainer di pintu masuk Terusan Suez mengalami peningkatan sebesar 25%. Otoritas maritim memproyeksikan rute alternatif mengitari Tanjung Harapan (Afrika Selatan) akan menambah biaya pelayaran maritim hingga 15% pada kuartal ini."
                    country="Sudan / Mesir"
                    category="Perdagangan"
                    date="Hari Ini"
                    risk="high"
                    icon="bi-globe-americas"
                    onclick="viewNewsModal('Terusan Suez Mengalami Penurunan Arus Kargo Akibat Hambatan Teritorial', 'Kemacetan kapal kontainer di pintu masuk Terusan Suez meningkat sebesar 25%. Hal ini memaksa sejumlah kapal kargo berputar mengitari rute Afrika Selatan yang berimbas pada penambahan waktu pelayaran selama 10 hari.')"
                />
            </div>

            <!-- SECTION 3: Trending Topics Tags -->
            <div class="card p-4 border-0">
                <h6 class="fw-bold text-dark mb-3"><i class="bi bi-graph-up text-primary me-2"></i>Tren Topik Intelijen</h6>
                <div class="d-flex flex-wrap gap-2">
                    <button class="btn btn-light btn-sm px-3 rounded-pill border tag-btn" onclick="filterByTag('Terusan Suez')">#Terusan Suez</button>
                    <button class="btn btn-light btn-sm px-3 rounded-pill border tag-btn" onclick="filterByTag('Krisis Energi')">#Krisis Energi</button>
                    <button class="btn btn-light btn-sm px-3 rounded-pill border tag-btn" onclick="filterByTag('Batubara')">#Batubara</button>
                    <button class="btn btn-light btn-sm px-3 rounded-pill border tag-btn" onclick="filterByTag('Inflasi')">#Inflasi</button>
                    <button class="btn btn-light btn-sm px-3 rounded-pill border tag-btn" onclick="filterByTag('Cuaca Ekstrem')">#Cuaca Ekstrem</button>
                </div>
            </div>

            <!-- SECTION 2: News Feed -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-newspaper text-primary me-2"></i>Umpan Berita Logistik Terkini</h5>
                
                <x-news-list id="news-feed-container">
                    <!-- News 1 -->
                    <div class="news-item-wrapper" data-title="Kenaikan Biaya Penumpukan Kargo Di Amerika Serikat Mengancam Eksportir Asia" data-summary="Otoritas pelabuhan Los Angeles mengumumkan penyesuaian tarif dwelling time kontainer di dermaga utama." data-region="america" data-category="ekonomi" data-risk="medium" data-country="Amerika Serikat">
                        <x-news-card 
                            title="Kenaikan Biaya Penumpukan Kargo Di Amerika Serikat Mengancam Eksportir Asia"
                            summary="Otoritas pelabuhan Los Angeles mengumumkan penyesuaian tarif dwelling time kontainer di dermaga utama."
                            country="Amerika Serikat"
                            category="Ekonomi"
                            date="1 jam yang lalu"
                            risk="medium"
                            icon="bi-currency-dollar"
                            onclick="viewNewsModal('Kenaikan Biaya Penumpukan Kargo Di Amerika Serikat Mengancam Eksportir Asia', 'Penyesuaian tarif dwelling time di Port of Los Angeles berpotensi menambah pengeluaran logistik bagi pabrikan manufaktur eksportir asal Tiongkok dan Asia Tenggara.')"
                        />
                    </div>

                    <!-- News 2 -->
                    <div class="news-item-wrapper" data-title="Pemerintah Indonesia Resmikan Gerbang Logistik Otomatis Berbasis IoT di Tanjung Priok" data-summary="Modernisasi terminal peti kemas Tanjung Priok rampung 100% demi efisiensi dwelling time logistik nasional." data-region="asia" data-category="logistik" data-risk="low" data-country="Indonesia">
                        <x-news-card 
                            title="Pemerintah Indonesia Resmikan Gerbang Logistik Otomatis Berbasis IoT di Tanjung Priok"
                            summary="Modernisasi terminal peti kemas Tanjung Priok rampung 100% demi efisiensi dwelling time logistik nasional."
                            country="Indonesia"
                            category="Logistik"
                            date="3 jam yang lalu"
                            risk="low"
                            icon="bi-cpu"
                            onclick="viewNewsModal('Pemerintah Indonesia Resmikan Gerbang Logistik Otomatis Berbasis IoT di Tanjung Priok', 'Penerapan sistem gerbang otomatis IoT mempercepat proses gate-in truk kontainer hingga memangkas waktu tunggu rata-rata sebesar 40%.')"
                        />
                    </div>

                    <!-- News 3 -->
                    <div class="news-item-wrapper" data-title="Eropa Alami Hambatan Pasokan Gas Akibat Kebocoran Pipa Laut Utara" data-summary="Kebocoran fasilitas penyaluran gas di Laut Utara memicu kenaikan inflasi energi di Jerman dan Belanda." data-region="europe" data-category="energi" data-risk="high" data-country="Jerman">
                        <x-news-card 
                            title="Eropa Alami Hambatan Pasokan Gas Akibat Kebocoran Pipa Laut Utara"
                            summary="Kebocoran fasilitas penyaluran gas di Laut Utara memicu kenaikan inflasi energi di Jerman dan Belanda."
                            country="Jerman / Eropa"
                            category="Energi"
                            date="5 jam yang lalu"
                            risk="high"
                            icon="bi-lightning-fill"
                            onclick="viewNewsModal('Eropa Alami Hambatan Pasokan Gas Akibat Kebocoran Pipa Laut Utara', 'Kebocoran pipa transmisi memicu penutupan aliran gas darurat. Harga gas di bursa berjangka melonjak hingga +8% dalam kurun waktu 24 jam.')"
                        />
                    </div>
                </x-news-list>
            </div>

            <!-- SECTION 4: News Timeline -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Rilis Informasi Rantai Pasok Hari Ini</h5>
                
                <x-timeline id="news-releases-timeline">
                    <!-- Release 1 -->
                    <div class="position-relative mb-3.5">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--danger); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Terusan Suez Ditutup Sementara</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Otoritas Pelayaran mengalihkan rute logistik maritim.</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>30 menit yang lalu</span>
                        </div>
                    </div>

                    <!-- Release 2 -->
                    <div class="position-relative mb-3.5">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--warning); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Tarif Dwelling Time AS Naik</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Otoritas Port of Los Angeles menyetujui kenaikan biaya.</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>1 jam yang lalu</span>
                        </div>
                    </div>

                    <!-- Release 3 -->
                    <div class="position-relative">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--success); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Digital Gate Priok Diuji</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Uji coba sistem gerbang IoT peti kemas dilaporkan berhasil.</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>3 jam yang lalu</span>
                        </div>
                    </div>
                </x-timeline>
            </div>

        </div>
    </div>

    <!-- Kolom Kanan (Intelligence Widgets) -->
    <div class="col-lg-4">
        <div class="d-flex flex-column gap-4">
            
            <!-- WIDGET 1: Top Headlines -->
            <x-widget-card title="Sorotan Berita Utama" icon="bi-award-fill">
                <div class="d-flex flex-column gap-3" style="font-size: 0.825rem;">
                    <div class="border-bottom pb-2">
                        <span class="badge bg-danger text-white mb-1" style="font-size: 0.65rem;">Krisis</span>
                        <h6 class="fw-bold text-dark small mb-0">Hambatan Rute Suez Hambat Ekspor Gandum Eropa</h6>
                    </div>
                    <div class="border-bottom pb-2">
                        <span class="badge bg-warning text-dark mb-1" style="font-size: 0.65rem;">Inflasi</span>
                        <h6 class="fw-bold text-dark small mb-0">Harga Batubara Global Menguat Akibat Musim Dingin</h6>
                    </div>
                    <div>
                        <span class="badge bg-primary text-white mb-1" style="font-size: 0.65rem;">Logistik</span>
                        <h6 class="fw-bold text-dark small mb-0">Digitalisasi IoT Port Indonesia Diresmikan</h6>
                    </div>
                </div>
            </x-widget-card>

            <!-- WIDGET 2: Top Risk Countries -->
            <x-widget-card title="Negara Fokus Risiko" icon="bi-flag-fill">
                <div class="d-flex flex-column gap-3" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                        <span>🇾🇪 Yaman (Sana'a)</span>
                        <span class="badge bg-danger text-white">Sangat Tinggi</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                        <span>🇺🇦 Ukraina (Kyiv)</span>
                        <span class="badge bg-danger text-white">Sangat Tinggi</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>🇨🇳 China (Beijing)</span>
                        <span class="badge bg-warning text-dark">Sedang</span>
                    </div>
                </div>
            </x-widget-card>

            <!-- WIDGET 3: Category Distribution Donut Chart -->
            <x-widget-card title="Penyebaran Berita Rantai Pasok" icon="bi-pie-chart-fill">
                <div class="border rounded-4 bg-light p-3 text-center d-flex flex-column align-items-center justify-content-center" style="height: 160px; background-color: #FAFCFF !important;">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <svg viewBox="0 0 36 36" class="w-100 h-100 transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#E2E8F0" stroke-width="3.5"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--primary)" stroke-dasharray="45 55" stroke-dashoffset="100" stroke-width="4"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--warning)" stroke-dasharray="25 75" stroke-dashoffset="55" stroke-width="4"></circle>
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--success)" stroke-dasharray="30 70" stroke-dashoffset="30" stroke-width="4"></circle>
                        </svg>
                    </div>
                    <span class="text-secondary small mt-2 d-block" style="font-size: 0.725rem;">Sebaran: 45% Logistik | 30% Cuaca | 25% Ekonomi</span>
                </div>
            </x-widget-card>

            <!-- WIDGET 4: News Impact indicator widget -->
            <x-widget-card title="Tingkat Ancaman Berita" icon="bi-shield-exclamation">
                <div class="p-3 border rounded-4 text-center" id="news-threat-box" style="background-color: rgba(239, 68, 68, 0.06); border-color: rgba(239, 68, 68, 0.15) !important;">
                    <span class="text-secondary small d-block mb-1">Index Kerawanan Distribusi</span>
                    <h4 class="fw-bold text-danger mb-0" id="news-threat-title"><i class="bi bi-exclamation-octagon-fill me-1.5"></i>Kritis (Critical)</h4>
                </div>
            </x-widget-card>

        </div>
    </div>
</div>

<!-- Modal component for reading news details -->
<div class="modal fade" id="readNewsModal" tabindex="-1" aria-labelledby="readNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="readNewsModalLabel"><i class="bi bi-newspaper text-primary me-2"></i>Intel Berita Maritim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <h5 class="fw-bold text-dark mb-3" id="modal-news-title">Judul Berita</h5>
                <p class="text-secondary small mb-0" id="modal-news-body">Isi ringkasan berita...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Run simulated loader
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
        }, 800);
    });

    // Client-side Filters & Search
    function applyNewsFilters() {
        const query = document.getElementById('search-news-input').value.toLowerCase();
        const region = document.getElementById('filter-news-region').value;
        const category = document.getElementById('filter-news-category').value;
        const risk = document.getElementById('filter-news-risk').value;

        const newsItems = Array.from(document.querySelectorAll('.news-item-wrapper'));
        let visibleCount = 0;

        newsItems.forEach(item => {
            const title = item.getAttribute('data-title').toLowerCase();
            const summary = item.getAttribute('data-summary').toLowerCase();
            const country = item.getAttribute('data-country').toLowerCase();
            const itemRegion = item.getAttribute('data-region');
            const itemCategory = item.getAttribute('data-category');
            const itemRisk = item.getAttribute('data-risk');

            const matchesSearch = title.includes(query) || summary.includes(query) || country.includes(query);
            const matchesRegion = (region === 'all' || itemRegion === region);
            const matchesCategory = (category === 'all' || itemCategory === category);
            
            let matchesRisk = true;
            if (risk === 'high') matchesRisk = (itemRisk === 'high' || itemRisk === 'critical');
            else if (risk === 'medium') matchesRisk = (itemRisk === 'medium');
            else if (risk === 'low') matchesRisk = (itemRisk === 'low');

            if (matchesSearch && matchesRegion && matchesCategory && matchesRisk) {
                item.style.display = 'block';
                visibleCount++;
            } else {
                item.style.display = 'none';
            }
        });

        // Toggle Grid vs States
        const grid = document.getElementById('main-content-grid');
        const emptyState = document.getElementById('empty-state-container');
        const errorState = document.getElementById('error-state-container');

        errorState.style.display = 'none';

        // We also show/hide Suez featured news depending on matches
        const featuredCard = document.getElementById('featured-news-area');
        if (query !== '' || region !== 'all' || category !== 'all' || risk !== 'all') {
            featuredCard.style.display = 'none';
        } else {
            featuredCard.style.display = 'block';
            visibleCount++; // add Suez
        }

        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyState.style.display = 'none';
        }
    }

    // Trigger filter by tag
    function filterByTag(tag) {
        document.getElementById('search-news-input').value = tag;
        applyNewsFilters();
    }

    // Refresh simulation trigger
    function triggerNewsSync() {
        const btn = document.getElementById('btn-sync-news');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Sinkronisasi...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Sinkronisasi Berita';
            btn.disabled = false;
            alert('Arus berita satelit logistik berhasil disinkronisasikan ke RSS Feed!');
        }, 1200);
    }

    // Skeleton loader simulation
    function simulateSkeletonLoading() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('error-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'block';

        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
            applyNewsFilters();
        }, 800);
    }

    // Empty state simulation
    function simulateEmptyState() {
        document.getElementById('search-news-input').value = 'BeritaXyzYangTidakAda';
        applyNewsFilters();
    }

    // Error state simulation
    function simulateErrorState() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('empty-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'none';
        document.getElementById('error-state-container').style.display = 'flex';
    }

    function retryFromError() {
        simulateSkeletonLoading();
    }

    // Reset filters
    function resetFilters() {
        document.getElementById('search-news-input').value = '';
        document.getElementById('filter-news-region').value = 'all';
        document.getElementById('filter-news-category').value = 'all';
        document.getElementById('filter-news-risk').value = 'all';
        applyNewsFilters();
    }

    // Modal view trigger
    function viewNewsModal(title, body) {
        document.getElementById('modal-news-title').textContent = title;
        document.getElementById('modal-news-body').textContent = body;
        
        const modal = new bootstrap.Modal(document.getElementById('readNewsModal'));
        modal.show();
    }
</script>
@endsection
