{{-- ============================================================
     TOOLBAR FILTER
     resources/views/dashboard/visualization/components/toolbar-filter.blade.php
     ============================================================ --}}

<div class="viz-toolbar mb-4">
    <div class="row g-2 align-items-center viz-toolbar-row">

        {{-- Search --}}
        <div class="col-12 col-sm-6 col-lg-3 viz-toolbar-search">
            <div class="input-group">
                <span class="input-group-text border-end-0" style="border-radius:10px 0 0 10px;">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text"
                       id="filterSearch"
                       class="form-control border-start-0"
                       placeholder="Cari negara..."
                       style="border-radius:0 10px 10px 0;"
                       oninput="FilterManager.apply()">
            </div>
        </div>

        {{-- Region --}}
        <div class="col-6 col-sm-4 col-lg-2">
            <select id="filterRegion" class="form-select" onchange="FilterManager.apply()">
                <option value="all">Semua Wilayah</option>
                <option value="asia">Asia</option>
                <option value="europe">Eropa</option>
                <option value="america">Amerika</option>
                <option value="africa">Afrika</option>
                <option value="oceania">Oceania</option>
            </select>
        </div>

        {{-- Year --}}
        <div class="col-6 col-sm-4 col-lg-2">
            <select id="filterYear" class="form-select" onchange="FilterManager.apply()">
                <option value="2024">Tahun 2024</option>
                <option value="2023">Tahun 2023</option>
                <option value="2022">Tahun 2022</option>
                <option value="2021">Tahun 2021</option>
            </select>
        </div>

        {{-- Currency --}}
        <div class="col-6 col-sm-4 col-lg-2">
            <select id="filterCurrency" class="form-select" onchange="FilterManager.apply()">
                <option value="all">Semua Mata Uang</option>
                <option value="IDR">IDR (Rupiah)</option>
                <option value="USD">USD (Dollar)</option>
                <option value="EUR">EUR (Euro)</option>
                <option value="CNY">CNY (Yuan)</option>
                <option value="SGD">SGD (Dollar SG)</option>
            </select>
        </div>

        {{-- Risk Level --}}
        <div class="col-6 col-sm-4 col-lg-2">
            <select id="filterRiskLevel" class="form-select" onchange="FilterManager.apply()">
                <option value="all">Semua Risk Level</option>
                <option value="low">Low Risk</option>
                <option value="medium">Medium Risk</option>
                <option value="high">High Risk</option>
            </select>
        </div>

        {{-- Chart Type --}}
        <div class="col-6 col-sm-4 col-lg-1">
            <select id="filterChartType" class="form-select" onchange="FilterManager.apply()">
                <option value="all">Semua Grafik</option>
                <option value="gdp">GDP</option>
                <option value="inflation">Inflasi</option>
                <option value="currency">Kurs</option>
                <option value="risk">Risiko</option>
            </select>
        </div>

        {{-- Action buttons --}}
        <div class="col-12 col-lg-auto ms-lg-auto d-flex gap-2 flex-wrap">
            {{-- Reset --}}
            <button id="btnResetFilters" type="button"
                    class="viz-toolbar-btn btn btn-outline-secondary"
                    data-bs-toggle="tooltip" title="Reset semua filter">
                <i class="bi bi-x-circle"></i>
                <span class="d-none d-md-inline">Reset</span>
            </button>

            {{-- Simulate states --}}
            <div class="dropdown">
                <button class="viz-toolbar-btn btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-gear"></i>
                    <span class="d-none d-md-inline">Simulasi</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3">
                    <li>
                        <button id="btnSimLoading" class="dropdown-item small py-2" type="button">
                            <i class="bi bi-hourglass-split me-2 text-primary"></i> Loading State
                        </button>
                    </li>
                    <li>
                        <button id="btnSimEmpty" class="dropdown-item small py-2" type="button">
                            <i class="bi bi-inbox me-2 text-warning"></i> Empty State
                        </button>
                    </li>
                    <li>
                        <button id="btnSimError" class="dropdown-item small py-2" type="button">
                            <i class="bi bi-exclamation-octagon me-2 text-danger"></i> Error State
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
