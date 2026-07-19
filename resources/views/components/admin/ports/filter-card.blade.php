{{-- ═══════════════════════════════════════════════════
     FILTER CARD COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/filter-card.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card port-toolbar-card border-0 shadow-sm">
    <div class="row g-3 align-items-end">
        {{-- Keyword --}}
        <div class="col-12 col-md-3 col-lg-3 text-start">
            <label for="filter-port-keyword" class="form-label small fw-semibold text-secondary mb-1.5">Nama Pelabuhan</label>
            <input type="text" id="filter-port-keyword" class="form-control form-control-sm" placeholder="Cari berdasarkan nama, negara, atau kode..." style="min-height: 38px; font-size: 0.85rem;" aria-label="Masukkan kata kunci pencarian pelabuhan">
        </div>

        {{-- Negara --}}
        <div class="col-12 col-md-3 col-lg-2 text-start">
            <label for="filter-port-country" class="form-label small fw-semibold text-secondary mb-1.5">Negara</label>
            <select id="filter-port-country" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter berdasarkan negara asal">
                <option value="">Semua Negara</option>
                <option value="Indonesia">Indonesia</option>
                <option value="Singapore">Singapore</option>
                <option value="United States">United States</option>
                <option value="China">China</option>
            </select>
        </div>

        {{-- Region --}}
        <div class="col-12 col-md-3 col-lg-1.5 text-start">
            <label for="filter-port-region" class="form-label small fw-semibold text-secondary mb-1.5">Region</label>
            <select id="filter-port-region" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter berdasarkan wilayah geografis">
                <option value="">Semua Region</option>
                <option value="Asia Tenggara">Asia Tenggara</option>
                <option value="Asia Timur">Asia Timur</option>
                <option value="Amerika Utara">Amerika Utara</option>
            </select>
        </div>

        {{-- Status --}}
        <div class="col-12 col-md-3 col-lg-1.5 text-start">
            <label for="filter-port-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
            <select id="filter-port-status" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter berdasarkan status operasional">
                <option value="">Semua Status</option>
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
                <option value="Maintenance">Maintenance</option>
            </select>
        </div>

        {{-- Urutkan Berdasarkan --}}
        <div class="col-12 col-md-3 col-lg-2 text-start">
            <label for="filter-port-sort" class="form-label small fw-semibold text-secondary mb-1.5">Urutkan Berdasarkan</label>
            <select id="filter-port-sort" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Urutkan dataset pelabuhan">
                <option value="">Pilih Pengurutan</option>
                <option value="name-asc">Nama A-Z</option>
                <option value="name-desc">Nama Z-A</option>
                <option value="date-newest">Update Terbaru</option>
                <option value="date-oldest">Update Terlama</option>
            </select>
        </div>

        {{-- Buttons --}}
        <div class="col-12 col-lg-2 d-flex gap-2">
            <button class="btn btn-primary btn-sm flex-grow-1" style="min-height: 38px; font-size: 0.85rem;" onclick="PortsFilter.applyFilters()" aria-label="Terapkan pencarian filter">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <button class="btn btn-light btn-sm border" style="min-height: 38px; font-size: 0.85rem;" onclick="PortsFilter.resetFilters()" aria-label="Reset seluruh filter pencarian">
                <i class="bi bi-x-circle me-1"></i> Reset
            </button>
        </div>
    </div>
</div>
