{{-- ═══════════════════════════════════════════════════
     ARTICLE MODAL DETAIL COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/modal-detail.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="articleDetailModal" tabindex="-1" aria-labelledby="articleDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="articleDetailModalLabel"><i class="bi bi-info-circle-fill text-primary me-2"></i>Detail Artikel Analisis</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4 text-start">
                <div class="row g-4">
                    {{-- Detail Information --}}
                    <div class="col-12 col-md-4">
                        <div class="border rounded-3 p-3 bg-light h-100">
                            {{-- Thumbnail Detail --}}
                            <div class="text-center mb-3">
                                <span class="d-block small fw-bold text-secondary mb-2">Thumbnail</span>
                                <img src="" id="detail-article-thumbnail" alt="Thumbnail Detail" class="rounded border" style="width: 150px; height: 110px; object-fit: cover;">
                            </div>

                            <div class="article-detail-row">
                                <span class="article-detail-label">Judul Artikel</span>
                                <span class="article-detail-value text-primary" id="detail-article-title">Title</span>
                            </div>

                            <div class="article-detail-row">
                                <span class="article-detail-label">Kategori</span>
                                <span class="article-detail-value" id="detail-article-category">Category</span>
                            </div>

                            <div class="article-detail-row">
                                <span class="article-detail-label">Penulis</span>
                                <span class="article-detail-value" id="detail-article-author">Author</span>
                            </div>

                            <div class="article-detail-row">
                                <span class="article-detail-label">Tanggal Publish</span>
                                <span class="article-detail-value" id="detail-article-published-at">Published At</span>
                            </div>

                            <div class="article-detail-row">
                                <span class="article-detail-label">Jumlah Views</span>
                                <span class="article-detail-value text-dark fw-bold" id="detail-article-views">0</span>
                            </div>

                            <div class="article-detail-row">
                                <span class="article-detail-label">Status</span>
                                <span class="badge" id="detail-article-status">Status</span>
                            </div>
                        </div>
                    </div>

                    {{-- Isi Artikel --}}
                    <div class="col-12 col-md-5">
                        <div class="border rounded-3 p-3 bg-white h-100 d-flex flex-column">
                            <span class="d-block small fw-bold text-secondary mb-2"><i class="bi bi-file-earmark-text text-primary me-1"></i>Isi Konten Artikel</span>
                            <div class="p-3 border rounded bg-light-subtle flex-grow-1" style="max-height: 380px; overflow-y: auto;">
                                <p class="text-secondary small mb-0" id="detail-article-content-preview" style="line-height: 1.7; font-size: 0.85rem; white-space: pre-line;">Content Preview</p>
                            </div>
                        </div>
                    </div>

                    {{-- Preview Card Component --}}
                    <div class="col-12 col-md-3">
                        <div class="card border border-light-subtle rounded-3 p-3 bg-light h-100 d-flex flex-column text-center">
                            <span class="d-block small fw-bold text-secondary mb-3"><i class="bi bi-card-text text-success me-1"></i>Simulasi Tampilan Artikel</span>
                            <x-admin.articles.preview-card />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal Footer --}}
            <div class="modal-footer">
                <button type="button" class="btn btn-light w-100" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>
