{{-- ═══════════════════════════════════════════════════
     ADMIN ARTICLE EDIT PAGE – Milestone 3.15D
     resources/views/admin/articles/edit.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Ubah Artikel Analisis - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/articles/article.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/editor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/modal.css') }}">
@endsection

@section('content')
    <div class="container py-4 text-start">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-9">
                <nav aria-label="Breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.articles') }}">Article Management</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Ubah Artikel</li>
                    </ol>
                </nav>

                <div class="card p-4 border-0 shadow-sm rounded-4 text-start">
                    <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-3">
                        <i class="bi bi-pencil-fill fs-4 text-warning"></i>
                        <h5 class="fw-bold text-dark mb-0">Ubah Artikel Analisis</h5>
                    </div>

                    <form class="needs-validation" novalidate onsubmit="event.preventDefault(); ArticlesCore.showToast('Artikel berhasil diperbarui.'); window.location.href='{{ route('admin.articles') }}';">
                        {{-- Judul Artikel --}}
                        <div class="mb-3">
                            <label for="edit-article-title" class="form-label small fw-semibold text-secondary mb-1.5">Judul Artikel</label>
                            <input type="text" id="edit-article-title" class="form-control" value="Global Supply Chain Disruptions in 2026: An Overview" required style="min-height: 44px;">
                            <div class="invalid-feedback">Judul artikel analisis wajib diisi.</div>
                        </div>

                        {{-- Kategori & Status --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
                                <label for="edit-article-category" class="form-label small fw-semibold text-secondary mb-1.5">Kategori</label>
                                <select id="edit-article-category" class="form-select" required style="min-height: 44px;">
                                    <option value="Supply Chain" selected>Supply Chain</option>
                                    <option value="Global Trade">Global Trade</option>
                                    <option value="Weather">Weather</option>
                                    <option value="Currency">Currency</option>
                                    <option value="Risk Analysis">Risk Analysis</option>
                                    <option value="Logistics">Logistics</option>
                                    <option value="Port">Port</option>
                                    <option value="Economy">Economy</option>
                                </select>
                            </div>
                            <div class="col-sm-6">
                                <label for="edit-article-status" class="form-label small fw-semibold text-secondary mb-1.5">Status Publikasi</label>
                                <select id="edit-article-status" class="form-select" required style="min-height: 44px;">
                                    <option value="Published" selected>Published</option>
                                    <option value="Draft">Draft</option>
                                    <option value="Archived">Archived</option>
                                </select>
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

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary flex-grow-1" style="min-height: 44px;">
                                Update Artikel
                            </button>
                            <a href="{{ route('admin.articles') }}" class="btn btn-light border" style="min-height: 44px; display:flex; align-items:center; justify-content:center;">
                                Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/articles/article.js') }}"></script>
    <script src="{{ asset('js/admin/articles/editor.js') }}"></script>
    <script src="{{ asset('js/admin/articles/upload.js') }}"></script>
    <script src="{{ asset('js/admin/articles/modal.js') }}"></script>
@endsection
