@extends('layouts.user.app')

@section('title', 'Notification Center - SupplyChain Platform')

@section('content')
<!-- Page Header Component -->
<x-page-header title="Notification Center" subtitle="Pantau seluruh notifikasi dan perubahan penting secara real-time." :breadcrumbs="['Notification Center' => '#']">
    <x-slot name="actions">
        <button class="btn btn-outline-primary me-2" id="btn-mark-all" onclick="triggerMarkAllRead()" style="min-height: 44px;">
            <i class="bi bi-check-all me-1"></i>Tandai Semua Dibaca
        </button>
        <button class="btn btn-primary" id="btn-sync-notify" onclick="triggerNewsSync()" style="min-height: 44px;">
            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Notifikasi
        </button>
    </x-slot>
</x-page-header>

<!-- Summary KPI Cards Row (6 Cards) -->
<div class="row g-4 mb-4">
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Total Notifikasi" value="12" description="Rilisan Log Hari Ini" icon="bi-bell" type="primary" id="kpi-total" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Belum Dibaca" value="3" description="Butuh Atensi" icon="bi-envelope" type="danger" id="kpi-unread" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Alert Kritis" value="2" description="Hambatan Merah" icon="bi-exclamation-octagon-fill" type="danger" id="kpi-critical" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Peringatan (Warning)" value="4" description="Status Siaga" icon="bi-exclamation-triangle" type="warning" id="kpi-warning" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Informasi" value="3" description="Pengumuman Sistem" icon="bi-info-circle" type="info" id="kpi-info" />
    </div>
    <div class="col-6 col-md-4 col-xl-2">
        <x-kpi-card title="Selesai (Resolved)" value="3" description="Telah Mitigasi" icon="bi-check-circle" type="success" id="kpi-resolved" />
    </div>
</div>

<!-- Header Toolbar Component -->
<x-toolbar>
    <!-- Search Box Component -->
    <div class="col-xl-3 col-lg-3 col-md-12 col-12">
        <x-search-input placeholder="Cari notifikasi..." id="search-notify-input" oninput="applyNotifyFilters()" />
    </div>
    
    <!-- Priority Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-notification-filter id="filter-notify-priority" onchange="applyNotifyFilters()">
            <option value="all">Semua Prioritas</option>
            <option value="critical">Critical (Kritis)</option>
            <option value="high">High (Tinggi)</option>
            <option value="medium">Medium (Sedang)</option>
            <option value="low">Low (Rendah)</option>
        </x-notification-filter>
    </div>

    <!-- Category Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-notification-filter id="filter-notify-category" onchange="applyNotifyFilters()">
            <option value="all">Semua Kategori</option>
            <option value="risk">Risiko</option>
            <option value="weather">Cuaca</option>
            <option value="port">Pelabuhan</option>
            <option value="country">Negara</option>
            <option value="news">Berita</option>
            <option value="currency">Valas</option>
            <option value="system">Sistem</option>
        </x-notification-filter>
    </div>

    <!-- Status Filter -->
    <div class="col-xl-2 col-lg-2 col-md-4 col-6">
        <x-notification-filter id="filter-notify-status" onchange="applyNotifyFilters()">
            <option value="all">Semua Status</option>
            <option value="unread">Belum Dibaca</option>
            <option value="read">Sudah Dibaca</option>
            <option value="archived">Diarsipkan</option>
        </x-notification-filter>
    </div>

    <!-- Sort Filter -->
    <div class="col-xl-3 col-lg-3 col-md-12 col-6">
        <x-notification-filter id="sort-notify-select" onchange="applyNotifyFilters()">
            <option value="newest">Urutkan: Terbaru</option>
            <option value="priority-desc">Urutkan: Prioritas</option>
            <option value="title">Urutkan: Judul</option>
        </x-notification-filter>
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
    <x-loading-state type="table" count="1" height="400px" />
</div>

<!-- Empty State Component -->
<div id="empty-state-container" style="display: none;">
    <x-empty-state title="Belum ada notifikasi." description="Kotak masuk Anda bersih. Tidak ada perubahan status atau peringatan baru." onclick="resetFilters()" />
</div>

<!-- Error State Component -->
<div id="error-state-container" style="display: none;">
    <x-error-state title="Integrasi Notifikasi Gagal." description="Gagal memuat log satelit maritim dan portal berita internasional. Silakan coba kembali." onclick="retryFromError()" />
</div>

<!-- Main Content Grid -->
<div id="main-content-grid" class="row g-4">
    <!-- Kolom Kiri: Notification List -->
    <div class="col-lg-7 col-xl-8">
        <div class="card p-4 border-0">
            <h5 class="fw-bold text-dark mb-3"><i class="bi bi-list-task text-primary me-2"></i>Daftar Peringatan & Log Sistem</h5>
            
            <div id="notify-list-container">
                <!-- Alert 1: Shanghai (Critical) -->
                <div class="notify-card-wrapper" data-id="alert-1" data-title="Kepadatan Kritis Di Port of Shanghai (CN)" data-description="Kepadatan kontainer menembus batas 95% akibat penumpukan kargo pasca-badai topan." data-priority="critical" data-category="port" data-country="China" data-date="5 Menit Lalu" data-status="unread" data-impact="Hambatan rantai pasok level kritis pada logistik semikonduktor dan otomotif." data-recommendation="Alihkan rute pelayaran maritim menuju Port of Ningbo atau Port of Singapore untuk menghindari biaya demurrage." data-module="Ports Center">
                    <x-notification-card 
                        id="alert-1"
                        title="Kepadatan Kritis Di Port of Shanghai (CN)"
                        description="Kepadatan kontainer menembus batas 95% akibat penumpukan kargo pasca-badai topan."
                        priority="critical"
                        category="port"
                        country="China"
                        date="5 Menit Lalu"
                        status="unread"
                    />
                </div>

                <!-- Alert 2: Terusan Suez (Critical) -->
                <div class="notify-card-wrapper" data-id="alert-2" data-title="Penundaan Pelayaran Melalui Terusan Suez" data-description="Terusan Suez mengalami penurunan arus kapal kargo sebesar 25% karena pengetatan keamanan teritorial." data-priority="critical" data-category="risk" data-country="Sudan / Mesir" data-date="12 Menit Lalu" data-status="unread" data-impact="Penundaan sandar kapal kontainer rute Asia-Eropa hingga 10 hari kerja." data-recommendation="Disarankan merutekan kapal maritim mengitari Tanjung Harapan (Afrika Selatan) dan mempersiapkan cadangan buffer stock." data-module="Risk Analysis">
                    <x-notification-card 
                        id="alert-2"
                        title="Penundaan Pelayaran Melalui Terusan Suez"
                        description="Terusan Suez mengalami penurunan arus kapal kargo sebesar 25% karena pengetatan keamanan teritorial."
                        priority="critical"
                        category="risk"
                        country="Sudan / Mesir"
                        date="12 Menit Lalu"
                        status="unread"
                    />
                </div>

                <!-- Alert 3: Dollar Kurs (High) -->
                <div class="notify-card-wrapper" data-id="alert-3" data-title="Volatilitas Valuta Asing USD ke IDR" data-description="Kurs Rupiah melemah -1.25% terhadap Dollar dalam 24 jam terakhir." data-priority="high" data-category="currency" data-country="Indonesia" data-date="1 Jam Lalu" data-status="unread" data-impact="Kenaikan biaya impor suku cadang manufaktur elekronik di Indonesia." data-recommendation="Lakukan hedging (lindung nilai) mata uang asing dan manfaatkan opsi kontrak berjangka valas." data-module="Exchange Rate">
                    <x-notification-card 
                        id="alert-3"
                        title="Volatilitas Valuta Asing USD ke IDR"
                        description="Kurs Rupiah melemah -1.25% terhadap Dollar dalam 24 jam terakhir."
                        priority="high"
                        category="currency"
                        country="Indonesia"
                        date="1 Jam Lalu"
                        status="unread"
                    />
                </div>

                <!-- Alert 4: Rotterdam (Low) -->
                <div class="notify-card-wrapper" data-id="alert-4" data-title="Operasional Port of Rotterdam Normal" data-description="Dwelling time peti kemas berada di angka rata-rata 2.1 hari (Normal)." data-priority="low" data-category="port" data-country="Belanda" data-date="3 Jam Lalu" data-status="read" data-impact="Tidak ada gangguan pada koridor maritim Eropa Barat." data-recommendation="Lanjutkan rencana sandar kapal kontainer terjadwal tanpa perubahan rute." data-module="Ports Center">
                    <x-notification-card 
                        id="alert-4"
                        title="Operasional Port of Rotterdam Normal"
                        description="Dwelling time peti kemas berada di angka rata-rata 2.1 hari (Normal)."
                        priority="low"
                        category="port"
                        country="Belanda"
                        date="3 Jam Lalu"
                        status="read"
                    />
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Notification Detail -->
    <div class="col-lg-5 col-xl-4">
        <div class="d-flex flex-column gap-4">
            <!-- Inspection Panel -->
            <div id="inspection-details-panel">
                <x-notification-detail 
                    title="Silakan Pilih Notifikasi"
                    category="Sistem"
                    priority="Rendah"
                    date="Live"
                    description="Pilih salah satu log peristiwa di kolom kiri untuk menampilkan rincian dampak, status analisis risiko, serta anjuran tindakan mitigasi operasional secara mendalam."
                    impact="Normal (Tidak ada dampak)"
                    recommendation="Tidak ada tindakan pencegahan maritim khusus yang perlu diambil."
                    module="Dashboard"
                />
            </div>

            <!-- WIDGET 1: Notification Statistics (Donut Chart SVG) -->
            <x-widget-card title="Proporsi Peringatan Sistem" icon="bi-pie-chart-fill">
                <div class="border rounded-4 bg-light p-3 text-center d-flex flex-column align-items-center justify-content-center" style="height: 160px; background-color: #FAFCFF !important;">
                    <div class="position-relative d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                        <svg viewBox="0 0 36 36" class="w-100 h-100 transform -rotate-90">
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="#E2E8F0" stroke-width="3.5"></circle>
                            <!-- Critical (40%) -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--danger)" stroke-dasharray="40 60" stroke-dashoffset="100" stroke-width="4"></circle>
                            <!-- Warning (40%) -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--warning)" stroke-dasharray="40 60" stroke-dashoffset="60" stroke-width="4"></circle>
                            <!-- Info (20%) -->
                            <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--info)" stroke-dasharray="20 80" stroke-dashoffset="20" stroke-width="4"></circle>
                        </svg>
                    </div>
                    <span class="text-secondary small mt-2 d-block" style="font-size: 0.725rem;">Rasio: 40% Kritis | 40% Siaga | 20% Informasi</span>
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

    // Inspector Selection Panel update
    function selectNotification(id) {
        const wrap = document.querySelector(`.notify-card-wrapper[data-id="${id}"]`);
        if (!wrap) return;

        const title = wrap.getAttribute('data-title');
        const description = wrap.getAttribute('data-description');
        const priority = wrap.getAttribute('data-priority');
        const category = wrap.getAttribute('data-category');
        const date = wrap.getAttribute('data-date');
        const impact = wrap.getAttribute('data-impact');
        const recommendation = wrap.getAttribute('data-recommendation');
        const module = wrap.getAttribute('data-module');

        // Update inspector elements
        document.getElementById('detail-title').textContent = title;
        document.getElementById('detail-description').textContent = description;
        document.getElementById('detail-date').textContent = date;
        document.getElementById('detail-impact').textContent = impact;
        document.getElementById('detail-recommendation').textContent = recommendation;
        document.getElementById('detail-module').textContent = module;

        // Color badge matches
        const catBadge = document.getElementById('detail-category-badge');
        catBadge.textContent = category.toUpperCase();
        
        const prioBadge = document.getElementById('detail-priority-badge');
        prioBadge.textContent = priority.toUpperCase();

        if (priority === 'critical') {
            prioBadge.className = 'badge bg-danger text-white';
        } else if (priority === 'high' || priority === 'medium') {
            prioBadge.className = 'badge bg-warning text-dark';
        } else {
            prioBadge.className = 'badge bg-success text-white';
        }

        // Active action button
        const actBtn = document.getElementById('detail-action-btn');
        actBtn.disabled = false;
        actBtn.setAttribute('onclick', `alert('Membuka modul ${module} untuk melihat visualisasi peristiwa.')`);
    }

    // Toggle Read/Unread Status
    function toggleRead(id) {
        const wrap = document.querySelector(`.notify-card-wrapper[data-id="${id}"]`);
        const card = document.getElementById(`notification-card-${id}`);
        if (!wrap || !card) return;

        const currentStatus = wrap.getAttribute('data-status');
        const badge = document.getElementById(`status-badge-${id}`);
        const titleText = card.querySelector('.notification-title-text');
        const readIcon = card.querySelector('.mark-read-btn i');

        const kpiUnread = document.getElementById('kpi-unread').querySelector('h3');

        if (currentStatus === 'unread') {
            wrap.setAttribute('data-status', 'read');
            badge.className = 'badge badge-success';
            badge.textContent = 'Sudah Dibaca';
            card.style.backgroundColor = '#F8FAFC';
            card.className = 'card p-3.5 border-0 mb-3 notification-card-item'; // remove borders
            titleText.style.fontWeight = '500';
            titleText.style.color = '#64748B';
            readIcon.className = 'bi bi-envelope-fill text-primary';
            
            // Adjust KPIs
            let num = parseInt(kpiUnread.textContent);
            if (num > 0) kpiUnread.textContent = num - 1;
        } else {
            wrap.setAttribute('data-status', 'unread');
            badge.className = 'badge badge-danger';
            badge.textContent = 'Belum Dibaca';
            card.style.backgroundColor = '#FFFFFF';
            
            const prio = wrap.getAttribute('data-priority');
            let borderClass = 'border-primary';
            if (prio === 'critical') borderClass = 'border-danger';
            else if (prio === 'high' || prio === 'medium') borderClass = 'border-warning';
            
            card.className = `card p-3.5 border-0 mb-3 notification-card-item border-start border-4 ${borderClass}`;
            titleText.style.fontWeight = '700';
            titleText.style.color = '#1E293B';
            readIcon.className = 'bi bi-check-circle-fill text-success';
            
            // Adjust KPIs
            let num = parseInt(kpiUnread.textContent);
            kpiUnread.textContent = num + 1;
        }
        applyNotifyFilters();
    }

    // Archive action
    function archiveNotification(id) {
        const wrap = document.querySelector(`.notify-card-wrapper[data-id="${id}"]`);
        if (wrap) {
            wrap.setAttribute('data-status', 'archived');
            alert('Notifikasi berhasil diarsipkan ke pusat riwayat.');
            applyNotifyFilters();
        }
    }

    // Mark all as read trigger
    function triggerMarkAllRead() {
        const unreadWrappers = Array.from(document.querySelectorAll('.notify-card-wrapper[data-status="unread"]'));
        unreadWrappers.forEach(wrap => {
            const id = wrap.getAttribute('data-id');
            toggleRead(id);
        });
        alert('Seluruh notifikasi berhasil ditandai sudah dibaca.');
    }

    // Filters and search logic
    function applyNotifyFilters() {
        const query = document.getElementById('search-notify-input').value.toLowerCase();
        const priority = document.getElementById('filter-notify-priority').value;
        const category = document.getElementById('filter-notify-category').value;
        const status = document.getElementById('filter-notify-status').value;
        const sortVal = document.getElementById('sort-notify-select').value;

        const wrappers = Array.from(document.querySelectorAll('.notify-card-wrapper'));
        const container = document.getElementById('notify-list-container');
        
        let visibleCount = 0;

        wrappers.forEach(wrap => {
            const title = wrap.getAttribute('data-title').toLowerCase();
            const desc = wrap.getAttribute('data-description').toLowerCase();
            const country = wrap.getAttribute('data-country').toLowerCase();
            const wrapPrio = wrap.getAttribute('data-priority');
            const wrapCat = wrap.getAttribute('data-category');
            const wrapStatus = wrap.getAttribute('data-status');

            const matchesSearch = title.includes(query) || desc.includes(query) || country.includes(query);
            const matchesPrio = (priority === 'all' || wrapPrio === priority);
            const matchesCat = (category === 'all' || wrapCat === category);
            const matchesStatus = (status === 'all' || wrapStatus === status);

            if (matchesSearch && matchesPrio && matchesCat && matchesStatus) {
                wrap.style.display = 'block';
                visibleCount++;
            } else {
                wrap.style.display = 'none';
            }
        });

        // Sorting
        if (visibleCount > 0) {
            wrappers.sort((a, b) => {
                if (sortVal === 'title') {
                    return a.getAttribute('data-title').localeCompare(b.getAttribute('data-title'));
                } else if (sortVal === 'priority-desc') {
                    const getPrioVal = p => p === 'critical' ? 4 : (p === 'high' ? 3 : (p === 'medium' ? 2 : 1));
                    return getPrioVal(b.getAttribute('data-priority')) - getPrioVal(a.getAttribute('data-priority'));
                }
                // default: newest/time
                return 0; // maintain original listing
            });
            wrappers.forEach(wrap => container.appendChild(wrap));
        }

        // Toggle Grid vs Empty
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
    function triggerNewsSync() {
        const btn = document.getElementById('btn-sync-notify');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Segarkan...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Notifikasi';
            btn.disabled = false;
            alert('Antrean pemberitahuan satelit maritim berhasil disegarkan!');
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
            applyNotifyFilters();
        }, 800);
    }

    // Empty state simulation
    function simulateEmptyState() {
        document.getElementById('search-notify-input').value = 'AlertXyzYangTidakAda';
        applyNotifyFilters();
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
        document.getElementById('search-notify-input').value = '';
        document.getElementById('filter-notify-priority').value = 'all';
        document.getElementById('filter-notify-category').value = 'all';
        document.getElementById('filter-notify-status').value = 'all';
        document.getElementById('sort-notify-select').value = 'newest';
        applyNotifyFilters();
    }
</script>
@endsection
