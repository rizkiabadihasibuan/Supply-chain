{{-- ═══════════════════════════════════════════════════
     ADMIN ARTICLE DETAIL PAGE – Milestone 3.15D
     resources/views/admin/articles/detail.blade.php
     ═══════════════════════════════════════════════════ --}}

@extends('layouts.admin.app')

@section('title', 'Detail Artikel Analisis - SupplyChain Platform')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/admin/articles/article.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/articles/table.css') }}">
@endsection

@section('content')
    <div class="container py-4 text-start">
        <nav aria-label="Breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.articles') }}">Article Management</a></li>
                <li class="breadcrumb-item active" aria-current="page">Detail Artikel</li>
            </ol>
        </nav>

        <div class="row g-4">
            {{-- Detail Information --}}
            <div class="col-12 col-lg-4">
                <div class="card p-4 border-0 shadow-sm rounded-4 h-100 text-start">
                    <div class="d-flex align-items-center gap-2 mb-4 border-bottom pb-3">
                        <i class="bi bi-info-circle-fill fs-4 text-primary"></i>
                        <h5 class="fw-bold text-dark mb-0">Informasi Artikel</h5>
                    </div>

                    <div class="border rounded-3 p-3 bg-light-subtle">
                        {{-- Thumbnail Detail --}}
                        <div class="text-center mb-3">
                            <span class="d-block small fw-bold text-secondary mb-2">Thumbnail</span>
                            <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=150&auto=format&fit=crop" alt="Thumbnail Detail" class="rounded border" style="width: 150px; height: 110px; object-fit: cover;">
                        </div>

                        <div class="article-detail-row">
                            <span class="article-detail-label">Judul Artikel</span>
                            <span class="article-detail-value text-primary">Global Supply Chain Disruptions in 2026: An Overview</span>
                        </div>

                        <div class="article-detail-row">
                            <span class="article-detail-label">Kategori</span>
                            <span class="article-detail-value">Supply Chain</span>
                        </div>

                        <div class="article-detail-row">
                            <span class="article-detail-label">Penulis</span>
                            <span class="article-detail-value">Administrator</span>
                        </div>

                        <div class="article-detail-row">
                            <span class="article-detail-label">Tanggal Publish</span>
                            <span class="article-detail-value">18-07-2026</span>
                        </div>

                        <div class="article-detail-row">
                            <span class="article-detail-label">Jumlah Views</span>
                            <span class="article-detail-value text-dark fw-bold">1,250</span>
                        </div>

                        <div class="article-detail-row">
                            <span class="article-detail-label">Status</span>
                            <span class="badge badge-status-published">Published</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Isi Artikel --}}
            <div class="col-12 col-lg-5">
                <div class="card border-0 rounded-4 p-4 bg-light shadow-sm h-100 text-start">
                    <span class="d-block small fw-bold text-secondary mb-3"><i class="bi bi-file-earmark-text text-primary me-1"></i>Isi Artikel Analisis</span>
                    <div class="p-3 border rounded bg-white w-100 flex-grow-1" style="min-height: 250px;">
                        <p class="text-secondary small mb-0" style="line-height: 1.8; font-size: 0.875rem; white-space: pre-line;">
                            Analisis mendalam mengenai gangguan rantai pasok global pada tahun 2026, memetakan rute pengapalan kritis dan risiko geopolitik.
                            
                            Dalam beberapa bulan terakhir, ketegangan di jalur pelayaran utama telah menyebabkan keterlambatan yang signifikan dan peningkatan biaya logistik. Laporan ini mengeksplorasi langkah-langkah mitigasi alternatif untuk memastikan kelangsungan operasional bisnis.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Preview Card Component --}}
            <div class="col-12 col-lg-3">
                <div class="card border-0 rounded-4 p-4 bg-light shadow-sm h-100 text-center">
                    <span class="d-block small fw-bold text-secondary mb-3"><i class="bi bi-card-text text-success me-1"></i>Simulasi Tampilan Artikel</span>
                    <x-admin.articles.preview-card />
                </div>
            </div>
        </div>

        <div class="d-flex mt-4">
            <a href="{{ route('admin.articles') }}" class="btn btn-light border w-100" style="min-height: 44px; display:flex; align-items:center; justify-content:center;">
                Kembali
            </a>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/admin/articles/preview.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof ArticlesPreview !== 'undefined') {
                ArticlesPreview.syncPreview(
                    'Global Supply Chain Disruptions in 2026: An Overview',
                    'Supply Chain',
                    'Analisis mendalam mengenai gangguan rantai pasok global pada tahun 2026, memetakan rute...',
                    'Administrator',
                    '18-07-2026',
                    'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=300&auto=format&fit=crop'
                );
            }
        });
    </script>
@endsection
