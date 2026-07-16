@extends('layouts.app')

@section('title', 'Risk Analysis - SupplyChain Platform')
@section('header_title', 'Risk Analysis Engine')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Analisis Risiko Terintegrasi</h5>
                    <p class="text-muted small mb-0">Menghitung akumulasi indeks risiko berdasarkan data cuaca, inflasi, mata uang, dan politik suatu negara.</p>
                </div>
                <button id="btn-refresh-risk" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> Cek Risiko
                </button>
            </div>
            
            <div class="alert alert-light border border-light-subtle rounded-3 p-4">
                <div class="text-center text-muted">
                    <i class="bi bi-calculator fs-2 mb-2 d-block"></i>
                    <p class="mb-0">Fitur scoring risiko terintegrasi disiapkan untuk dikembangkan di RiskScoringService pada tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('btn-refresh-risk').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        
        try {
            const response = await SupplyChainAPI.fetch('risk');
            alert('AJAX Endpoint connected: ' + response.message);
        } catch (error) {
            alert('Error fetching Risk API: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Cek Risiko';
        }
    });
</script>
@endsection
