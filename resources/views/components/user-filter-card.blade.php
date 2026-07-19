{{-- ═══════════════════════════════════════════════════
     USER FILTER CARD COMPONENT – Milestone 3.15B
     resources/views/components/user-filter-card.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card user-toolbar-card border-0">
    <div class="row g-3 align-items-end">
        {{-- Keyword --}}
        <div class="col-12 col-md-3">
            <label for="filter-keyword" class="form-label small fw-semibold text-secondary mb-1.5">Kata Kunci</label>
            <input type="text" id="filter-keyword" class="form-control form-control-sm" placeholder="Cari nama atau email..." style="min-height: 38px; font-size: 0.85rem;">
        </div>

        {{-- Role --}}
        <div class="col-12 col-md-2">
            <label for="filter-role" class="form-label small fw-semibold text-secondary mb-1.5">Peran (Role)</label>
            <select id="filter-role" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;">
                <option value="">Semua Peran</option>
                <option value="Admin">Admin</option>
                <option value="User">User</option>
            </select>
        </div>

        {{-- Status --}}
        <div class="col-12 col-md-2">
            <label for="filter-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
            <select id="filter-status" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;">
                <option value="">Semua Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="Suspended">Suspended</option>
            </select>
        </div>

        {{-- Tanggal Bergabung --}}
        <div class="col-12 col-md-2">
            <label for="filter-joined-date" class="form-label small fw-semibold text-secondary mb-1.5">Tanggal Bergabung</label>
            <input type="date" id="filter-joined-date" class="form-control form-control-sm" style="min-height: 38px; font-size: 0.85rem;">
        </div>

        {{-- Buttons --}}
        <div class="col-12 col-md-3 d-flex gap-2">
            <button class="btn btn-primary btn-sm flex-grow-1" style="min-height: 38px; font-size: 0.85rem;" onclick="UsersFilter.applyFilters()">
                <i class="bi bi-search me-1.5"></i> Cari
            </button>
            <button class="btn btn-light btn-sm border" style="min-height: 38px; font-size: 0.85rem;" onclick="UsersFilter.resetFilters()">
                <i class="bi bi-x-circle me-1.5"></i> Reset
            </button>
        </div>
    </div>
</div>
