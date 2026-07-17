@extends('layouts.app')

@section('title', 'Global Monitoring Center - SupplyChain Platform')

@section('content')
<!-- Page Header Component -->
<x-page-header title="Global Monitoring Center" subtitle="Pantau seluruh negara, pelabuhan, berita, cuaca, mata uang, dan analisis risiko yang menjadi fokus pemantauan Anda." :breadcrumbs="['Monitoring Center' => '#']">
    <x-slot name="actions">
        <button class="btn btn-primary" id="btn-sync-monitor" onclick="triggerMonitorSync()" style="min-height: 44px;">
            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Pemantauan
        </button>
    </x-slot>
</x-page-header>

<!-- Summary KPI Cards Row (6 Cards) -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Negara Dipantau" value="4" description="Wilayah Aktif" icon="bi-flag" type="primary" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Pelabuhan Dipantau" value="3" description="Dermaga Utama" icon="bi-anchor" type="info" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Berita Dipantau" value="5" description="RSS Umpan Berita" icon="bi-newspaper" type="warning" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Mata Uang Dipantau" value="2" description="Kurs Valas" icon="bi-currency-exchange" type="success" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Alert Aktif" value="3" description="Butuh Respon" icon="bi-bell-fill" type="danger" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Update Terakhir" value="Live" description="Koneksi Satelit" icon="bi-check-circle" type="success" />
    </div>
</div>

<!-- Header Toolbar Component -->
<x-toolbar>
    <!-- Search Box Component -->
    <div class="col-xl-3 col-lg-3 col-md-12 col-12">
        <x-search-input placeholder="Cari negara, pelabuhan, berita..." id="search-monitor-input" oninput="applyMonitorFilters()" />
    </div>
    
    <!-- Category Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-filter-dropdown id="filter-monitor-category" onchange="applyMonitorFilters()">
            <option value="all">Semua Kategori</option>
            <option value="country">Negara</option>
            <option value="port">Pelabuhan</option>
            <option value="currency">Mata Uang</option>
            <option value="weather">Cuaca</option>
            <option value="news">Berita</option>
            <option value="risk">Analisis Risiko</option>
        </x-filter-dropdown>
    </div>

    <!-- Risk Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-filter-dropdown id="filter-monitor-risk" onchange="applyMonitorFilters()">
            <option value="all">Semua Risiko</option>
            <option value="critical">Critical (Kritis)</option>
            <option value="high">High (Tinggi)</option>
            <option value="medium">Medium (Sedang)</option>
            <option value="low">Low (Rendah)</option>
        </x-filter-dropdown>
    </div>

    <!-- Status Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-filter-dropdown id="filter-monitor-status" onchange="applyMonitorFilters()">
            <option value="all">Semua Status</option>
            <option value="monitoring">Monitoring (Aktif)</option>
            <option value="paused">Paused (Ditangguhkan)</option>
            <option value="resolved">Resolved (Selesai)</option>
        </x-filter-dropdown>
    </div>

    <!-- Sort Filter -->
    <div class="col-xl-3 col-lg-3 col-md-12 col-6">
        <x-filter-dropdown id="sort-monitor-select" onchange="applyMonitorFilters()">
            <option value="name">Urutkan: Nama</option>
            <option value="risk-desc">Urutkan: Risiko Tertinggi</option>
            <option value="category">Urutkan: Kategori</option>
        </x-filter-dropdown>
    </div>

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
</x-toolbar>

<!-- Skeleton Loading wrapper -->
<div id="skeleton-container" style="display: none;">
    <x-loading-state type="card" count="4" height="240px" />
</div>

<!-- Empty State Component -->
<div id="empty-state-container" style="display: none;">
    <x-empty-state title="Belum ada item monitoring." description="Anda belum menambahkan negara atau pelabuhan ke dalam radar pengawasan." onclick="resetFilters()" />
</div>

<!-- Error State Component -->
<div id="error-state-container" style="display: none;">
    <x-error-state title="Koneksi Pusat Monitoring Terputus." description="Gagal menghubungkan ke data satelit IBM Control Tower. Silakan coba kembali." onclick="retryFromError()" />
</div>

<!-- Main Content Grid -->
<div id="main-content-grid" class="row g-4">
    <!-- Kolom Kiri (Pinned, Monitoring List, Timeline) -->
    <div class="col-lg-8">
        <div class="d-flex flex-column gap-4">
            
            <!-- SECTION 2: Pinned Monitoring (Favorite Items) -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pin-angle-fill text-warning me-2"></i>Pemantauan Prioritas (Pinned)</h5>
                
                <div id="pinned-items-container" class="row g-3">
                    <!-- Pinned items will be injected here dynamically or shown when state matches -->
                    <div class="col-12 text-center py-4 text-secondary small border rounded-4 border-dashed" id="no-pinned-msg" style="border-style: dashed !important;">
                        <i class="bi bi-star me-2"></i>Sematkan item penting untuk ditampilkan di area prioritas ini.
                    </div>
                </div>
            </div>

            <!-- SECTION 1: Monitoring Grid List -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-grid-fill text-primary me-2"></i>Semua Radar Pengawasan Aktif</h5>
                
                <div class="row g-3" id="monitor-list-grid">
                    <!-- Item 1: Tanjung Priok (Port) -->
                    <div class="col-md-6 col-12 monitor-card-wrapper" data-category="port" data-risk="low" data-status="monitoring">
                        <x-monitor-card 
                            id="item-tanjung-priok"
                            name="Tanjung Priok (ID)"
                            category="port"
                            status="monitoring"
                            risk="low"
                            date="Baru Saja"
                            icon="bi-anchor"
                            :isPinned="false"
                        />
                    </div>

                    <!-- Item 2: Shanghai (Port) -->
                    <div class="col-md-6 col-12 monitor-card-wrapper" data-category="port" data-risk="critical" data-status="monitoring">
                        <x-monitor-card 
                            id="item-shanghai"
                            name="Port of Shanghai (CN)"
                            category="port"
                            status="monitoring"
                            risk="critical"
                            date="5 Menit Lalu"
                            icon="bi-anchor"
                            :isPinned="false"
                        />
                    </div>

                    <!-- Item 3: Dollar Kurs (Currency) -->
                    <div class="col-md-6 col-12 monitor-card-wrapper" data-category="currency" data-risk="medium" data-status="monitoring">
                        <x-monitor-card 
                            id="item-usd-idr"
                            name="Kurs USD ke IDR"
                            category="currency"
                            status="monitoring"
                            risk="medium"
                            date="12 Menit Lalu"
                            icon="bi-currency-exchange"
                            :isPinned="false"
                        />
                    </div>

                    <!-- Item 4: Sudan (Country) -->
                    <div class="col-md-6 col-12 monitor-card-wrapper" data-category="country" data-risk="critical" data-status="paused">
                        <x-monitor-card 
                            id="item-sudan"
                            name="Sudan (Khartoum)"
                            category="country"
                            status="paused"
                            risk="critical"
                            date="1 Jam Lalu"
                            icon="bi-flag"
                            :isPinned="false"
                        />
                    </div>
                </div>
            </div>

            <!-- SECTION 3: Monitoring Activity Timeline -->
            <div class="card p-4 border-0">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-primary me-2"></i>Log Perubahan Status Pengawasan</h5>
                
                <x-timeline id="monitoring-log-timeline">
                    <div class="position-relative mb-3.5">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--danger); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Peningkatan Level Kerawanan Shanghai</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Skor risiko merambat naik ke Critical akibat cuaca buruk maritim.</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>5 menit yang lalu</span>
                        </div>
                    </div>
                    <div class="position-relative">
                        <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--warning); border: 2px solid #FFFFFF;"></div>
                        <div class="small">
                            <span class="text-dark fw-bold d-block">Penangguhan Pengawasan Sudan</span>
                            <span class="text-secondary d-block" style="font-size: 0.725rem;">Pengawasan wilayah dihentikan sementara atas permintaan administrator.</span>
                            <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>1 jam yang lalu</span>
                        </div>
                    </div>
                </x-timeline>
            </div>

        </div>
    </div>

    <!-- Kolom Kanan (Widgets) -->
    <div class="col-lg-4">
        <div class="d-flex flex-column gap-4">
            
            <!-- WIDGET 1: Today's Summary -->
            <x-widget-card title="Ringkasan Aktivitas Hari Ini" icon="bi-card-list">
                <div class="d-flex flex-column gap-2.5" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Objek Aktif Dipantau:</span>
                        <span class="text-dark fw-bold">3 Objek</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Objek Ditangguhkan:</span>
                        <span class="text-dark fw-bold">1 Objek</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Pesan Peringatan Kritis:</span>
                        <span class="text-danger fw-bold">2 Peringatan</span>
                    </div>
                </div>
            </x-widget-card>

            <!-- WIDGET 2: Risk Distribution (Donut Chart SVG) -->
            <x-widget-card title="Sebaran Risiko Radar Pengawasan" icon="bi-pie-chart-fill">
                <div class="border rounded-4 bg-light p-3 text-center d-flex flex-column align-items-center justify-content-center" style="height: 160px; background-color: #FAFCFF !important;">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <svg viewBox="0 0 36 36" class="w-100 h-100 transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#E2E8F0" stroke-width="3.5"></circle>
                            <!-- Critical (50%) -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--danger)" stroke-dasharray="50 50" stroke-dashoffset="100" stroke-width="4"></circle>
                            <!-- Medium (25%) -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--warning)" stroke-dasharray="25 75" stroke-dashoffset="50" stroke-width="4"></circle>
                            <!-- Low (25%) -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--success)" stroke-dasharray="25 75" stroke-dashoffset="25" stroke-width="4"></circle>
                        </svg>
                    </div>
                    <span class="text-secondary small mt-2 d-block" style="font-size: 0.725rem;">Rasio: 50% Kritis | 25% Sedang | 25% Rendah</span>
                </div>
            </x-widget-card>

            <!-- WIDGET 3: Most Active Monitoring -->
            <x-widget-card title="Radar Teraktif" icon="bi-activity">
                <div class="d-flex flex-column gap-3" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2">
                        <div>
                            <span class="fw-bold text-dark d-block">Port of Shanghai (CN)</span>
                            <span class="text-secondary small">5 Kali Pemicu Peringatan</span>
                        </div>
                        <x-badge type="danger" text="Tinggi" />
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold text-dark d-block">Kurs USD ke IDR</span>
                            <span class="text-secondary small">3 Kali Perubahan Valuta</span>
                        </div>
                        <x-badge type="warning" text="Sedang" />
                    </div>
                </div>
            </x-widget-card>

            <!-- WIDGET 4: Upcoming Alerts -->
            <x-widget-card title="Alert Mendatang" icon="bi-calendar-event">
                <div class="d-flex flex-column gap-2" style="font-size: 0.8rem;">
                    <div class="p-2 border rounded-3 bg-light" style="background-color: #FAFCFF !important;">
                        <span class="text-dark fw-bold d-block" style="font-size: 0.75rem;">Estimasi Dwelling Time AS</span>
                        <span class="text-secondary small d-block">Evaluasi rilis data Port of LA dilakukan pukul 08:00 besok.</span>
                    </div>
                </div>
            </x-widget-card>

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

    // Client-side Pinning & Unpinning
    function togglePin(itemId) {
        const card = document.getElementById(`monitor-card-${itemId}`);
        if (!card) return;

        const wrapper = card.parentElement;
        const pinnedContainer = document.getElementById('pinned-items-container');
        const listGrid = document.getElementById('monitor-list-grid');
        const isPinned = card.getAttribute('data-pinned') === 'true';

        // Toggle icon and flag
        const starIcon = card.querySelector('.pin-toggle-btn i');

        if (isPinned) {
            // Unpin card: Move back to original Grid
            card.setAttribute('data-pinned', 'false');
            starIcon.className = 'bi bi-star fs-5';
            
            // Move wrapper column back to standard grid
            listGrid.appendChild(wrapper);
        } else {
            // Pin card: Move to Pinned container
            card.setAttribute('data-pinned', 'true');
            starIcon.className = 'bi bi-star-fill text-warning fs-5';
            
            // Move wrapper column to pinned area
            pinnedContainer.appendChild(wrapper);
        }

        // Toggle no-pinned message placeholder
        const pinnedWrappers = pinnedContainer.querySelectorAll('.monitor-card-wrapper');
        const msg = document.getElementById('no-pinned-msg');
        if (pinnedWrappers.length > 0) {
            msg.style.display = 'none';
        } else {
            msg.style.display = 'block';
        }
    }

    // Toggle Pause/Resume status
    function togglePause(itemId) {
        const card = document.getElementById(`monitor-card-${itemId}`);
        if (!card) return;

        const currentStatus = card.getAttribute('data-status');
        const statusBadge = card.querySelector('.status-badge-text');
        const pauseBtnIcon = card.querySelector(`[onclick="togglePause('${itemId}')"] i`);

        if (currentStatus === 'paused') {
            card.setAttribute('data-status', 'monitoring');
            statusBadge.className = 'status-badge-text fw-semibold text-success';
            statusBadge.textContent = '● Aktif Memantau';
            pauseBtnIcon.className = 'bi bi-pause-fill';
            alert(`Radar pengawasan ${card.getAttribute('data-name')} diaktifkan kembali.`);
        } else {
            card.setAttribute('data-status', 'paused');
            statusBadge.className = 'status-badge-text fw-semibold text-warning';
            statusBadge.textContent = '⏸️ Ditangguhkan';
            pauseBtnIcon.className = 'bi bi-play-fill';
            alert(`Radar pengawasan ${card.getAttribute('data-name')} ditangguhkan.`);
        }
    }

    // Delete item card
    function deleteMonitorItem(itemId) {
        if (confirm('Apakah Anda yakin ingin menghapus stasiun pengawasan ini?')) {
            const card = document.getElementById(`monitor-card-${itemId}`);
            if (card) {
                const wrapper = card.parentElement;
                wrapper.remove();
                
                // Toggle empty placeholder if zero items remain
                const remaining = document.querySelectorAll('.monitor-card-wrapper');
                if (remaining.length === 0) {
                    simulateEmptyState();
                } else {
                    alert('Radar pengawasan dihapus.');
                }
            }
        }
    }

    // Search and filter logic
    function applyMonitorFilters() {
        const query = document.getElementById('search-monitor-input').value.toLowerCase();
        const category = document.getElementById('filter-monitor-category').value;
        const risk = document.getElementById('filter-monitor-risk').value;
        const status = document.getElementById('filter-monitor-status').value;
        const sortVal = document.getElementById('sort-monitor-select').value;

        const wrappers = Array.from(document.querySelectorAll('.monitor-card-wrapper'));
        const listGrid = document.getElementById('monitor-list-grid');

        let visibleCount = 0;

        wrappers.forEach(wrap => {
            const card = wrap.querySelector('.monitor-card-item');
            if (!card) return;

            const name = card.getAttribute('data-name').toLowerCase();
            const cat = card.getAttribute('data-category');
            const rowRisk = card.getAttribute('data-risk');
            const rowStatus = card.getAttribute('data-status');

            const matchesSearch = name.includes(query);
            const matchesCat = (category === 'all' || cat === category);
            const matchesRisk = (risk === 'all' || rowRisk === risk);
            const matchesStatus = (status === 'all' || rowStatus === status);

            if (matchesSearch && matchesCat && matchesRisk && matchesStatus) {
                wrap.style.display = 'block';
                visibleCount++;
            } else {
                wrap.style.display = 'none';
            }
        });

        // Sorting
        if (visibleCount > 0) {
            wrappers.sort((a, b) => {
                const cardA = a.querySelector('.monitor-card-item');
                const cardB = b.querySelector('.monitor-card-item');
                if (!cardA || !cardB) return 0;

                if (sortVal === 'name') {
                    return cardA.getAttribute('data-name').localeCompare(cardB.getAttribute('data-name'));
                } else if (sortVal === 'risk-desc') {
                    const getRiskVal = r => r === 'critical' ? 4 : (r === 'high' ? 3 : (r === 'medium' ? 2 : 1));
                    return getRiskVal(cardB.getAttribute('data-risk')) - getRiskVal(cardA.getAttribute('data-risk'));
                } else if (sortVal === 'category') {
                    return cardA.getAttribute('data-category').localeCompare(cardB.getAttribute('data-category'));
                }
                return 0;
            });
            
            // Re-append based on sort
            wrappers.forEach(wrap => {
                // Check if card is pinned to maintain placement
                const card = wrap.querySelector('.monitor-card-item');
                if (card && card.getAttribute('data-pinned') === 'false') {
                    listGrid.appendChild(wrap);
                }
            });
        }

        // Toggle Grid vs Empty States
        const grid = document.getElementById('main-content-grid');
        const emptyState = document.getElementById('empty-state-container');
        const errorState = document.getElementById('error-state-container');

        errorState.style.display = 'none';

        if (visibleCount === 0) {
            grid.style.display = 'none';
            emptyState.style.display = 'flex';
        } else {
            grid.style.display = 'flex';
            emptyState.style.display = 'none';
        }
    }

    // Refresh simulation trigger
    function triggerMonitorSync() {
        const btn = document.getElementById('btn-sync-monitor');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Segarkan...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Pemantauan';
            btn.disabled = false;
            alert('Status pemantauan satelit maritim berhasil disegarkan!');
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
            applyMonitorFilters();
        }, 800);
    }

    // Empty state simulation
    function simulateEmptyState() {
        document.getElementById('search-monitor-input').value = 'RadarXyz';
        applyMonitorFilters();
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
        document.getElementById('search-monitor-input').value = '';
        document.getElementById('filter-monitor-category').value = 'all';
        document.getElementById('filter-monitor-risk').value = 'all';
        document.getElementById('filter-monitor-status').value = 'all';
        document.getElementById('sort-monitor-select').value = 'name';
        applyMonitorFilters();
    }
</script>
@endsection
