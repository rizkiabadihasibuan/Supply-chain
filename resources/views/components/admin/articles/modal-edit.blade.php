{{-- ═══════════════════════════════════════════════════
     ARTICLE MODAL EDIT COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/modal-edit.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="articleEditModal" tabindex="-1" aria-labelledby="articleEditModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="articleEditModalLabel"><i class="bi bi-pencil-fill text-warning me-2"></i>Edit Artikel</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4 text-start">
                <form id="form-edit-article" class="needs-validation" novalidate onsubmit="event.preventDefault(); ArticlesModal.saveEdit();">
                    
                    {{-- Judul & Slug --}}
                    <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                            <label for="edit-article-title" class="form-label small fw-semibold text-secondary mb-1.5">Judul Artikel</label>
                            <input type="text" id="edit-article-title" class="form-control" required style="min-height: 44px;">
                            <div class="invalid-feedback">Judul artikel analisis wajib diisi.</div>
                        </div>
                        <div class="col-sm-6">
                            <label for="edit-article-slug" class="form-label small fw-semibold text-secondary mb-1.5">Slug Artikel (Otomatis)</label>
                            <input type="text" id="edit-article-slug" class="form-control" required style="min-height: 44px;" readonly>
                        </div>
                    </div>

                    {{-- Kategori & Status & Tanggal Publish --}}
                    <div class="row g-3 mb-3">
                        <div class="col-sm-4">
                            <label for="edit-article-category" class="form-label small fw-semibold text-secondary mb-1.5">Kategori</label>
                            <select id="edit-article-category" class="form-select" required style="min-height: 44px;">
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
                        <div class="col-sm-4">
                            <label for="edit-article-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                            <select id="edit-article-status" class="form-select" required style="min-height: 44px;">
                                <option value="Draft">Draft</option>
                                <option value="Published">Published</option>
                                <option value="Archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label for="edit-article-published-date" class="form-label small fw-semibold text-secondary mb-1.5">Tanggal Publish</label>
                            <input type="text" id="edit-article-published-date" class="form-control" style="min-height: 44px;">
                        </div>
                    </div>

                    {{-- Upload Thumbnail Component --}}
                    <x-admin.articles.thumbnail-upload type="edit" />

                    {{-- Ringkasan Artikel --}}
                    <div class="mb-3">
                        <label for="edit-article-summary" class="form-label small fw-semibold text-secondary mb-1.5">Ringkasan Artikel</label>
                        <textarea id="edit-article-summary" class="form-control" rows="2" placeholder="Tuliskan ringkasan singkat artikel..." required>Analisis mendalam mengenai gangguan rantai pasok global pada tahun 2026.</textarea>
                        <div class="invalid-feedback">Ringkasan artikel wajib diisi.</div>
                    </div>

                    {{-- Editor Placeholder Component --}}
                    <x-admin.articles.editor-placeholder type="edit" />

                    <div class="d-none">
                        <button type="submit" id="btn-submit-edit-article-form"></button>
                    </div>
                </form>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer d-flex gap-2">
                <button type="button" class="btn btn-primary flex-grow-1" onclick="document.getElementById('btn-submit-edit-article-form').click()">
                    Update
                </button>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                    Batal
                </button>
            </div>
        </div>
    </div>
</div>
