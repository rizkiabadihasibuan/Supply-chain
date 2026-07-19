{{-- ═══════════════════════════════════════════════════
     ADMIN ARTICLE CREATE PAGE – Milestone 3.15D
     resources/views/admin/articles/create.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Tambah Artikel Analisis - SupplyChain Platform')

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
                        <li class="breadcrumb-item active" aria-current="page">Tambah Artikel</li>
                    </ol>
                </nav>

                <div class="card p-4 border-0 shadow-sm rounded-4 text-start">
                    <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-3">
                        <i class="bi bi-plus-circle-fill fs-4 text-primary"></i>
                        <h5 class="fw-bold text-dark mb-0">Tambah Artikel Baru</h5>
                    </div>

                    <form class="needs-validation" novalidate onsubmit="event.preventDefault(); ArticlesCore.showToast('Artikel berhasil ditambahkan.'); window.location.href='{{ route('admin.articles') }}';">
                        {{-- Judul Artikel --}}
                        <div class="mb-3">
                            <label for="create-article-title" class="form-label small fw-semibold text-secondary mb-1.5">Judul Artikel</label>
                            <input type="text" id="create-article-title" class="form-control" placeholder="Tulis judul artikel analisis..." required style="min-height: 44px;">
                            <div class="invalid-feedback">Judul artikel analisis wajib diisi.</div>
                        </div>

                        {{-- Kategori & Status --}}
                        <div class="row g-3 mb-3">
                            <div class="col-sm-6">
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
                            <div class="col-sm-6">
                                <label for="create-article-status" class="form-label small fw-semibold text-secondary mb-1.5">Status Publikasi</label>
                                <select id="create-article-status" class="form-select" required style="min-height: 44px;">
                                    <option value="Published">Published</option>
                                    <option value="Draft" selected>Draft</option>
                                    <option value="Archived">Archived</option>
                                </select>
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

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary flex-grow-1" style="min-height: 44px;">
                                Simpan Artikel
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
