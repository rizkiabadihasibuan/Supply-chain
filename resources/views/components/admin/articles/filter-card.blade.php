{{-- ═══════════════════════════════════════════════════
     FILTER CARD COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/filter-card.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card article-toolbar-card border-0 shadow-sm">
    <div class="row g-3 align-items-end">
        {{-- Judul Artikel --}}
        <div class="col-12 col-md-3 text-start">
            <label for="filter-article-keyword" class="form-label small fw-semibold text-secondary mb-1.5">Judul Artikel</label>
            <input type="text" id="filter-article-keyword" class="form-control form-control-sm" placeholder="Cari judul artikel..." style="min-height: 38px; font-size: 0.85rem;" aria-label="Masukkan judul artikel">
        </div>

        {{-- Kategori --}}
        <div class="col-12 col-md-3 col-lg-2.5 text-start">
            <label for="filter-article-category" class="form-label small fw-semibold text-secondary mb-1.5">Kategori</label>
            <select id="filter-article-category" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter berdasarkan kategori">
                <option value="">Semua Kategori</option>
                <option value="Supply Chain">Supply Chain</option>
                <option value="Global Trade">Global Trade</option>
                <option value="Weather">Weather</option>
                <option value="Currency">Currency</option>
                <option value="Risk Analysis">Risk Analysis</option>
                <option value="Logistics">Logistics</option>
                <option value="Port">Port</option>
                <option value="Economy">Economy</option>
            </select>
        </div>

        {{-- Status --}}
        <div class="col-12 col-md-2 text-start">
            <label for="filter-article-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
            <select id="filter-article-status" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter berdasarkan status">
                <option value="">Semua Status</option>
                <option value="Published">Published</option>
                <option value="Draft">Draft</option>
                <option value="Archived">Archived</option>
            </select>
        </div>

        {{-- Tanggal Publish --}}
        <div class="col-12 col-md-2 text-start">
            <label for="filter-article-date" class="form-label small fw-semibold text-secondary mb-1.5">Tanggal Publish</label>
            <input type="date" id="filter-article-date" class="form-control form-control-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter berdasarkan tanggal publikasi">
        </div>

        {{-- Buttons --}}
        <div class="col-12 col-lg-2.5 d-flex gap-2">
            <button class="btn btn-primary btn-sm flex-grow-1" style="min-height: 38px; font-size: 0.85rem;" onclick="ArticlesFilter.applyFilters()" aria-label="Cari artikel">
                <i class="bi bi-search me-1"></i> Cari
            </button>
            <button class="btn btn-light btn-sm border" style="min-height: 38px; font-size: 0.85rem;" onclick="ArticlesFilter.resetFilters()" aria-label="Reset pencarian filter">
                <i class="bi bi-x-circle me-1"></i> Reset
            </button>
        </div>
    </div>
</div>
