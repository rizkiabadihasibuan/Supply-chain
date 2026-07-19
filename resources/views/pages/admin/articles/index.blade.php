{{-- ═══════════════════════════════════════════════════
     ADMIN ARTICLE MANAGEMENT INDEX – Milestone 3.16A
     resources/views/pages/admin/articles/index.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Article Management - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/articles/article.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/table.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/card.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/responsive.css') }}">
@endsection

@section('content')
    <div class="admin-articles-wrapper text-start">
        {{-- ══════ HEADER ══════ --}}
        <x-admin.articles.article-header />

        {{-- Container Success Alert --}}
        <div id="articles-success-alert-container" style="display: none;" class="mb-2">
            <x-admin.articles.success-alert />
        </div>

        {{-- Container Error Simulator --}}
        <div id="articles-error-container" style="display: none;" class="mb-2">
            <x-admin.articles.error-state />
        </div>

        {{-- Skeleton Loading Container --}}
        <div id="articles-skeleton-container" class="mb-2">
            <x-admin.articles.loading-state />
        </div>

        {{-- ══════ MAIN CONTENT AREA ══════ --}}
        <div id="articles-main-content" style="display: none;">

            {{-- ══════ ACTION TOOLBAR ══════ --}}
            <div class="article-toolbar-container">
                <div class="btn-group" role="group" aria-label="Toolbar Aksi Artikel">
                    <button class="btn btn-primary d-flex align-items-center gap-1.5" data-bs-toggle="modal" data-bs-target="#articleCreateModal" style="min-height: 38px; font-size: 0.85rem;" title="Tambah artikel analisis baru ke platform" aria-label="Tambah Artikel Baru">
                        <i class="bi bi-plus-circle-fill"></i> Tambah Artikel
                    </button>
                    <button class="btn btn-secondary d-flex align-items-center gap-1.5 text-white" onclick="ArticlesCore.refreshData()" style="min-height: 38px; font-size: 0.85rem;" title="Refresh list artikel dari server" aria-label="Refresh Dataset">
                        <i class="bi bi-arrow-clockwise"></i> Refresh
                    </button>
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle d-flex align-items-center gap-1.5" data-bs-toggle="dropdown" aria-expanded="false" style="min-height: 38px; font-size: 0.85rem;" aria-label="Ekspor data artikel">
                            <i class="bi bi-download"></i> Export Data
                        </button>
                        <ul class="dropdown-menu shadow-sm">
                            <li><button class="dropdown-item small" type="button" onclick="ArticlesExport.triggerExport('csv')"><i class="bi bi-filetype-csv text-success me-1.5"></i>Export CSV</button></li>
                            <li><button class="dropdown-item small" type="button" onclick="ArticlesExport.triggerExport('excel')"><i class="bi bi-file-earmark-excel text-primary me-1.5"></i>Export Excel</button></li>
                            <li><button class="dropdown-item small" type="button" onclick="ArticlesExport.triggerExport('pdf')"><i class="bi bi-file-pdf text-danger me-1.5"></i>Export PDF</button></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- ══════ STATISTICS (4 Cards) ══════ --}}
            <div class="row g-3 mb-4">
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.articles.statistics-card title="Total Artikel" value="125 Artikel" icon="file-earmark-text" color="primary" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.articles.statistics-card title="Published" value="94" icon="check-circle" color="success" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.articles.statistics-card title="Draft" value="21" icon="pencil-square" color="warning" />
                </div>
                <div class="col-12 col-sm-6 col-md-3">
                    <x-admin.articles.statistics-card title="Archived" value="10" icon="archive" color="secondary" />
                </div>
            </div>

            {{-- ══════ FILTER SECTION ══════ --}}
            <div class="mb-4">
                <div class="card article-toolbar-card border-0 shadow-sm">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-3 text-start">
                            <label for="filter-article-keyword" class="form-label small fw-semibold text-secondary mb-1.5">Judul Artikel</label>
                            <input type="text" id="filter-article-keyword" class="form-control form-control-sm" placeholder="Cari berdasarkan judul artikel..." style="min-height: 38px; font-size: 0.85rem;" aria-label="Cari judul artikel">
                        </div>
                        <div class="col-12 col-md-2 text-start">
                            <label for="filter-article-category" class="form-label small fw-semibold text-secondary mb-1.5">Kategori</label>
                            <select id="filter-article-category" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter kategori">
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
                        <div class="col-12 col-md-2 text-start">
                            <label for="filter-article-status" class="form-label small fw-semibold text-secondary mb-1.5">Status</label>
                            <select id="filter-article-status" class="form-select form-select-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter status">
                                <option value="">Semua Status</option>
                                <option value="Published">Published</option>
                                <option value="Draft">Draft</option>
                                <option value="Archived">Archived</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 text-start">
                            <label for="filter-article-author" class="form-label small fw-semibold text-secondary mb-1.5">Penulis</label>
                            <input type="text" id="filter-article-author" class="form-control form-control-sm" placeholder="Nama penulis..." style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter penulis">
                        </div>
                        <div class="col-12 col-md-2 text-start">
                            <label for="filter-article-date" class="form-label small fw-semibold text-secondary mb-1.5">Tanggal Publish</label>
                            <input type="date" id="filter-article-date" class="form-control form-control-sm" style="min-height: 38px; font-size: 0.85rem;" aria-label="Filter tanggal">
                        </div>
                        <div class="col-12 col-lg-3 d-flex gap-2">
                            <button class="btn btn-primary btn-sm flex-grow-1" style="min-height: 38px; font-size: 0.85rem;" onclick="ArticlesFilter.applyFilters()" aria-label="Terapkan filter">
                                <i class="bi bi-search me-1"></i> Cari
                            </button>
                            <button class="btn btn-light btn-sm border" style="min-height: 38px; font-size: 0.85rem;" onclick="ArticlesFilter.resetFilters()" aria-label="Reset filter">
                                <i class="bi bi-x-circle me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ══════ ARTICLE DATA TABLE ══════ --}}
            <div class="mb-4">
                <x-admin.articles.article-table>
                    <x-admin.articles.article-row no="1" title="Global Supply Chain Disruptions in 2026: An Overview" category="Supply Chain" status="Published" author="Administrator" publishedAt="18-07-2026" views="1,250" thumbnail="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=150&auto=format&fit=crop" content="Analisis mendalam mengenai gangguan rantai pasok global pada tahun 2026." />
                    <x-admin.articles.article-row no="2" title="Understanding Exchange Rate Fluctuations in Trade" category="Currency" status="Published" author="Administrator" publishedAt="17-07-2026" views="850" thumbnail="https://images.unsplash.com/photo-1618042164219-62c820f10723?q=80&w=150&auto=format&fit=crop" content="Bagaimana pergerakan mata uang asing berdampak pada biaya pengiriman barang." />
                    <x-admin.articles.article-row no="3" title="Port Congestion Trends in Southeast Asia" category="Port" status="Published" author="Administrator" publishedAt="16-07-2026" views="450" thumbnail="https://images.unsplash.com/photo-1578575437130-527eed3abbec?q=80&w=150&auto=format&fit=crop" content="Laporan khusus tren kemacetan pelabuhan utama di kawasan Asia Tenggara." />
                    <x-admin.articles.article-row no="4" title="Weather-Induced Risks in Maritime Logistics" category="Weather" status="Draft" author="Administrator" publishedAt="—" views="0" thumbnail="" content="Menilai dampak cuaca ekstrem pada rute pelayaran dan strategi mitigasi risiko." />
                    <x-admin.articles.article-row no="5" title="Green Logistics: The Future of Global Freight" category="Logistics" status="Archived" author="Administrator" publishedAt="10-06-2026" views="120" thumbnail="https://images.unsplash.com/photo-1521898284481-a5ec348cb555?q=80&w=150&auto=format&fit=crop" content="Membahas inisiatif logistik hijau dan dekarbonisasi dalam transportasi kargo global." />
                </x-admin.articles.article-table>
            </div>

            {{-- ══════ PAGINATION ══════ --}}
            <div class="mt-4 mb-4">
                <x-admin.articles.pagination />
            </div>

            {{-- Empty State --}}
            <div id="articles-empty-state" style="display: none;" class="mb-4">
                <x-admin.articles.empty-state />
            </div>

        </div>

        {{-- Simulator --}}
        <div class="d-flex justify-content-end gap-2 pt-3 border-top mb-4">
            <button class="btn btn-sm btn-outline-danger" onclick="ArticlesCore.simulateError()" aria-label="Simulasikan data error">
                ⚡ Simulasikan Error Layanan
            </button>
            <button class="btn btn-sm btn-outline-secondary" onclick="simulateEmptyState()" id="btn-toggle-articles-empty" aria-label="Simulasikan data kosong">
                ⚡ Simulasikan Kosong
            </button>
        </div>
    </div>

    {{-- ══════ MODAL DIALOGS ══════ --}}
    <x-admin.articles.modal-create />
    <x-admin.articles.modal-edit />
    <x-admin.articles.modal-detail />
    <x-admin.articles.modal-delete />
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(el => new bootstrap.Tooltip(el));
        });

        function simulateEmptyState() {
            const tableContainer = document.querySelector('.article-table-container');
            const empty = document.getElementById('articles-empty-state');
            const btn = document.getElementById('btn-toggle-articles-empty');
            if (tableContainer.style.display !== 'none') {
                tableContainer.style.display = 'none';
                empty.style.display = 'block';
                btn.textContent = '⚡ Pulihkan Artikel';
            } else {
                tableContainer.style.display = 'block';
                empty.style.display = 'none';
                btn.textContent = '⚡ Simulasikan Kosong';
            }
        }
    </script>
    <script src="{{ asset('js/admin/articles/article.js') }}"></script>
    <script src="{{ asset('js/admin/articles/modal.js') }}"></script>
    <script src="{{ asset('js/admin/articles/table.js') }}"></script>
    <script src="{{ asset('js/admin/articles/filter.js') }}"></script>
    <script src="{{ asset('js/admin/articles/editor.js') }}"></script>
    <script src="{{ asset('js/admin/articles/upload.js') }}"></script>
    <script src="{{ asset('js/admin/articles/preview.js') }}"></script>
@endsection
