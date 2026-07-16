@extends('layouts.app')

@section('title', 'News - SupplyChain Platform')
@section('header_title', 'Global Supply Chain News Feed')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Berita Global & Analisis Sentimen</h5>
                    <p class="text-muted small mb-0">Memantau kabar rantai pasokan dunia dan menganalisis dampaknya terhadap logistik.</p>
                </div>
                <button id="btn-refresh-news" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> Cek Berita
                </button>
            </div>
            
            <div class="alert alert-light border border-light-subtle rounded-3 p-4">
                <div class="text-center text-muted">
                    <i class="bi bi-card-text fs-2 mb-2 d-block"></i>
                    <p class="mb-0">Fitur umpan berita global disiapkan untuk terintegrasi dengan GNews API pada tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('btn-refresh-news').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        
        try {
            const response = await SupplyChainAPI.fetch('news');
            alert('AJAX Endpoint connected: ' + response.message);
        } catch (error) {
            alert('Error fetching News API: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Cek Berita';
        }
    });
</script>
@endsection
