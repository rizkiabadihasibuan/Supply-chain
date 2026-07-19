{{-- ═══════════════════════════════════════════════════
     ARTICLE MODAL CREATE COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/modal-create.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="articleCreateModal" tabindex="-1" aria-labelledby="articleCreateModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="articleCreateModalLabel"><i class="bi bi-plus-circle-fill text-primary me-2"></i>Tambah Artikel Baru</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4 text-start">
                <form id="form-create-article" class="needs-validation" novalidate onsubmit="event.preventDefault();">
                    
                    {{-- Judul & Slug --}}
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="create-article-title" class="form-label small fw-semibold text-secondary mb-1.5">Judul Artikel</label>
                            <input type="text" id="create-article-title" class="form-control" placeholder="Tulis judul artikel analisis..." required style="min-height: 44px;">
                            <div class="invalid-feedback">Judul artikel analisis wajib diisi.</div>
                        </div>
                        <div class="col-sm-6">
                            <label for="create-article-slug" class="form-label small fw-semibold text-secondary mb-1.5">Slug Artikel (Otomatis)</label>
                            <input type="text" id="create-article-slug" class="form-control" placeholder="slug-artikel-otomatis" required style="min-height: 44px;" readonly>
                        </div>
                    </div>

                    {{-- Kategori & Status & Tanggal Publish --}}
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label for="create-article-category" class="form-label small fw-semibold text-secondary mb-1.5">Kategori</label>
                            <select id="create-article-category" class="form-select" required style="min-height: 44px;">
                                <option value="" selected disabled>Pilih Kategori</option>
                                <option value="Supply Chain">Supply Chain</option>
                                <option value="Global Trade">Global Trade</option>
                                <option value="Weather">Weather</option>
                                <option value="Currency">Currency</option>
                                <option value="Risk Analysis">Risk Analysis</option>
                                <option value="Logistics">Logistics</option>
                                <option value="Port">Port</option>
                                <option value="Economy">Economy</option>
                            </select>
                            <div class="invalid-feedback">Pilih salah satu kategori.</div>
                        </div>
                        <div class="col-sm-4">
                            <label for="create-article-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                            <select id="create-article-status" class="form-select" required style="min-height: 44px;">
                                <option value="Draft" selected>Draft</option>
                                <option value="Published">Published</option>
                                <option value="Archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="create-article-date" class="form-label small fw-semibold text-secondary mb-1.5">Tanggal Publish</label>
                            <input type="date" id="create-article-date" class="form-control" style="min-height: 44px;">
                        </div>
                    </div>

                    {{-- Upload Thumbnail Component --}}
                    <x-admin.articles.thumbnail-upload type="create" />

                    {{-- Ringkasan Artikel --}}
                    <div class="mb-3">
                        <label for="create-article-summary" class="form-label small fw-semibold text-secondary mb-1.5">Ringkasan Artikel</label>
                        <textarea id="create-article-summary" class="form-control" rows="2" placeholder="Tuliskan ringkasan singkat artikel..." required></textarea>
                        <div class="invalid-feedback">Ringkasan artikel wajib diisi.</div>
                    </div>

                    {{-- Editor Placeholder Component --}}
                    <x-admin.articles.editor-placeholder type="create" />

                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-primary flex-grow-1" onclick="ArticlesModal.saveCreate('Published')">
                    Simpan
                </button>
                <button type="button" class="btn btn-secondary text-white" onclick="ArticlesModal.saveCreate('Draft')">
                    Simpan Draft
                </button>
                <button type="button" class="btn btn-light border" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
