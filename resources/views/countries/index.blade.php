@extends('layouts.app')

@section('title', 'Negara - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0">

    <!-- Header & Filter Row -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h4 class="fw-bold text-dark mb-1">Pengawasan Risiko Negara</h4>
                        <p class="text-secondary small mb-0">Kelola dan monitor indikator risiko, ekonomi, serta cuaca port negara mitra logistik.</p>
                    </div>
                    
                    <!-- Filters -->
                    <div class="d-flex flex-wrap gap-2.5">
                        <div class="search-wrapper">
                            <i class="bi bi-search"></i>
                            <input type="text" id="search-country" placeholder="Cari negara..." class="form-control ps-5" style="width: 200px;">
                        </div>
                        <select id="filter-risk" class="form-select" style="width: 180px;">
                            <option value="all">Semua Risiko</option>
                            <option value="high">Risiko Tinggi</option>
                            <option value="medium">Risiko Sedang</option>
                            <option value="low">Risiko Rendah</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Workspace Row -->
    <div class="row g-4">
        
        <!-- Left Column: Mini-Map and Table -->
        <div class="col-lg-8">
            <div class="d-flex flex-column gap-4">
                
                <!-- Mini-Map Box -->
                <div class="card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Area Geografis Terpantau</h6>
                    <div class="border rounded-4 d-flex align-items-center justify-content-center overflow-hidden position-relative" style="height: 200px; background-color: #FAFCFF; background-image: radial-gradient(#E2E8F0 1.2px, transparent 1.2px); background-size: 20px 20px;">
                        <div class="text-center position-relative z-index-1 p-3">
                            <span class="badge badge-info mb-2 text-uppercase" style="font-size: 0.65rem;">Radar Pemantau</span>
                            <h6 class="text-dark fw-semibold mb-1">Visualisasi Koordinat Mini-Map</h6>
                            <p class="text-secondary small mb-0">Modul peta mini Leaflet.js tersemat secara statis di area ini.</p>
                        </div>
                        <div class="position-absolute w-100 h-100 top-0 start-0 opacity-10" style="border: 2px dashed rgba(14, 165, 233, 0.3); pointer-events: none;"></div>
                    </div>
                </div>

                <!-- Country Table -->
                <div class="card p-4 border-0 shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-table text-primary me-2"></i>Daftar Pengawasan Negara</h6>
                        <button class="btn btn-light btn-sm border px-3" onclick="location.reload();">
                            <i class="bi bi-arrow-clockwise"></i> Perbarui Data
                        </button>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="countries-table">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama Negara</th>
                                    <th>Kawasan</th>
                                    <th>Populasi</th>
                                    <th>Indeks Risiko</th>
                                    <th>Status Hub</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="country-row" data-country-id="ID" data-name="Indonesia" data-flag="🇮🇩" data-gdp="USD 1.37 Triliun" data-inflation="2.8%" data-pop="275 Juta" data-curr="Rupiah (IDR)" data-weather="28°C / Hujan" data-risk="0.12" data-risk-level="success" data-status="Terhubung">
                                    <td class="fw-bold text-dark">ID</td>
                                    <td>Indonesia</td>
                                    <td>Asia Tenggara</td>
                                    <td>275 Juta</td>
                                    <td><span class="badge badge-success">Rendah - 0.12</span></td>
                                    <td><i class="bi bi-check-circle-fill text-success me-1"></i> Terhubung</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-light btn-sm border px-2.5 btn-select-country">Pilih</button>
                                            <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-2.5 text-decoration-none" style="font-size: 0.775rem;">Detail</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="country-row" data-country-id="SG" data-name="Singapura" data-flag="🇸🇬" data-gdp="USD 466 Miliar" data-inflation="1.9%" data-pop="5.9 Juta" data-curr="Dolar Singapura (SGD)" data-weather="31°C / Cerah" data-risk="0.08" data-risk-level="success" data-status="Terhubung">
                                    <td class="fw-bold text-dark">SG</td>
                                    <td>Singapura</td>
                                    <td>Asia Tenggara</td>
                                    <td>5.9 Juta</td>
                                    <td><span class="badge badge-success">Rendah - 0.08</span></td>
                                    <td><i class="bi bi-check-circle-fill text-success me-1"></i> Terhubung</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-light btn-sm border px-2.5 btn-select-country">Pilih</button>
                                            <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-2.5 text-decoration-none" style="font-size: 0.775rem;">Detail</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="country-row" data-country-id="JP" data-name="Jepang" data-flag="🇯🇵" data-gdp="USD 4.23 Triliun" data-inflation="2.5%" data-pop="125 Juta" data-curr="Yen Jepang (JPY)" data-weather="25°C / Berawan" data-risk="2.80" data-risk-level="warning" data-status="Siaga">
                                    <td class="fw-bold text-dark">JP</td>
                                    <td>Jepang</td>
                                    <td>Asia Timur</td>
                                    <td>125 Juta</td>
                                    <td><span class="badge badge-warning">Sedang - 2.80</span></td>
                                    <td><i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Siaga</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-light btn-sm border px-2.5 btn-select-country">Pilih</button>
                                            <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-2.5 text-decoration-none" style="font-size: 0.775rem;">Detail</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="country-row" data-country-id="US" data-name="Amerika Serikat" data-flag="🇺🇸" data-gdp="USD 26.85 Triliun" data-inflation="3.2%" data-pop="333 Juta" data-curr="Dolar AS (USD)" data-weather="19°C / Berangin" data-risk="4.89" data-risk-level="danger" data-status="Gangguan">
                                    <td class="fw-bold text-dark">US</td>
                                    <td>Amerika Serikat</td>
                                    <td>Amerika Utara</td>
                                    <td>333 Juta</td>
                                    <td><span class="badge badge-danger">Tinggi - 4.89</span></td>
                                    <td><i class="bi bi-x-circle-fill text-danger me-1"></i> Gangguan</td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button class="btn btn-light btn-sm border px-2.5 btn-select-country">Pilih</button>
                                            <a href="{{ route('countries.detail') }}" class="btn btn-light btn-sm border px-2.5 text-decoration-none" style="font-size: 0.775rem;">Detail</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Detail and Stats -->
        <div class="col-lg-4">
            <div class="d-flex flex-column gap-4">
                
                <!-- Detail Negara Panel -->
                <div class="card p-4 border-0 shadow-sm" id="detail-panel">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Detail Negara Terpilih</h6>
                    
                    <div class="text-center pb-3 border-bottom mb-3">
                        <span id="detail-flag" class="fs-1 d-block mb-1">🇮🇩</span>
                        <h5 id="detail-name" class="fw-bold text-dark mb-1">Indonesia</h5>
                        <span id="detail-status" class="badge badge-success">Terhubung</span>
                    </div>

                    <div class="d-flex flex-column gap-2.5">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Produk Domestik Bruto (GDP)</span>
                            <span id="detail-gdp" class="text-dark fw-semibold small">USD 1.37 Triliun</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Tingkat Inflasi</span>
                            <span id="detail-inflation" class="text-dark fw-semibold small">2.8%</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Total Populasi</span>
                            <span id="detail-pop" class="text-dark fw-semibold small">275 Juta</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Mata Uang</span>
                            <span id="detail-curr" class="text-dark fw-semibold small">Rupiah (IDR)</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-secondary small">Kondisi Cuaca Port</span>
                            <span id="detail-weather" class="text-dark fw-semibold small">28°C / Hujan</span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                            <span class="text-dark fw-bold small">Skor Indeks Risiko</span>
                            <span id="detail-risk" class="badge badge-success">Rendah - 0.12</span>
                        </div>
                    </div>
                </div>

                <!-- Statistik Widget Panel -->
                <div class="card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart-fill text-primary me-2"></i>Penyebaran Kategori Risiko</h6>
                    <div class="border rounded-4 bg-light p-3 text-center d-flex flex-column align-items-center justify-content-center" style="height: 160px; background-color: #FAFCFF !important;">
                        <!-- Vector Donut Mockup -->
                        <div class="position-relative d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <svg viewBox="0 0 36 36" class="w-100 h-100 transform -rotate-90">
                                <circle cx="18" cy="18" r="15.915" fill="none" stroke="#E2E8F0" stroke-width="3"></circle>
                                <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--danger)" stroke-dasharray="25 75" stroke-dashoffset="100" stroke-width="4"></circle>
                                <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--warning)" stroke-dasharray="25 75" stroke-dashoffset="75" stroke-width="4"></circle>
                                <circle cx="18" cy="18" r="15.915" fill="none" stroke="var(--success)" stroke-dasharray="50 50" stroke-dashoffset="50" stroke-width="4"></circle>
                            </svg>
                        </div>
                        <span class="text-secondary small mt-2 d-block" style="font-size: 0.75rem;">Sebaran: 50% Rendah | 25% Sedang | 25% Tinggi</span>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('.country-row');
        const searchInput = document.getElementById('search-country');
        const filterSelect = document.getElementById('filter-risk');

        // Elements of the detail panel to update
        const detailFlag = document.getElementById('detail-flag');
        const detailName = document.getElementById('detail-name');
        const detailStatus = document.getElementById('detail-status');
        const detailGdp = document.getElementById('detail-gdp');
        const detailInflation = document.getElementById('detail-inflation');
        const detailPop = document.getElementById('detail-pop');
        const detailCurr = document.getElementById('detail-curr');
        const detailWeather = document.getElementById('detail-weather');
        const detailRisk = document.getElementById('detail-risk');

        // Function to update the side detail panel based on clicked country row
        function selectCountry(row) {
            const data = row.dataset;
            detailFlag.textContent = data.flag;
            detailName.textContent = data.name;
            detailStatus.textContent = data.status;

            // Update status badge class
            detailStatus.className = 'badge';
            if (data.riskLevel === 'success') {
                detailStatus.classList.add('badge-success');
            } else if (data.riskLevel === 'warning') {
                detailStatus.classList.add('badge-warning');
            } else {
                detailStatus.classList.add('badge-danger');
            }

            detailGdp.textContent = data.gdp;
            detailInflation.textContent = data.inflation;
            detailPop.textContent = data.pop;
            detailCurr.textContent = data.curr;
            detailWeather.textContent = data.weather;
            detailRisk.textContent = (data.riskLevel === 'success' ? 'Rendah - ' : data.riskLevel === 'warning' ? 'Sedang - ' : 'Tinggi - ') + data.risk;

            detailRisk.className = 'badge';
            if (data.riskLevel === 'success') {
                detailRisk.classList.add('badge-success');
            } else if (data.riskLevel === 'warning') {
                detailRisk.classList.add('badge-warning');
            } else {
                detailRisk.classList.add('badge-danger');
            }
        }

        // Attach event listeners to Pick/Pilih buttons
        rows.forEach(row => {
            row.querySelector('.btn-select-country').addEventListener('click', function(e) {
                e.stopPropagation();
                selectCountry(row);
                // Highlight row
                rows.forEach(r => r.classList.remove('table-primary'));
                row.classList.add('table-primary');
            });
            row.addEventListener('click', function() {
                selectCountry(row);
                rows.forEach(r => r.classList.remove('table-primary'));
                row.classList.add('table-primary');
            });
        });

        // Search filter
        searchInput.addEventListener('input', function() {
            filterRows();
        });

        // Dropdown filter
        filterSelect.addEventListener('change', function() {
            filterRows();
        });

        function filterRows() {
            const query = searchInput.value.toLowerCase();
            const riskFilter = filterSelect.value;

            rows.forEach(row => {
                const name = row.dataset.name.toLowerCase();
                const level = row.dataset.riskLevel; // success, warning, danger
                
                let matchesSearch = name.includes(query);
                let matchesRisk = true;

                if (riskFilter === 'high') {
                    matchesRisk = (level === 'danger');
                } else if (riskFilter === 'medium') {
                    matchesRisk = (level === 'warning');
                } else if (riskFilter === 'low') {
                    matchesRisk = (level === 'success');
                }

                if (matchesSearch && matchesRisk) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }
    });
</script>
@endsection
