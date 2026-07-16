@extends('layouts.app')

@section('title', 'Currency - SupplyChain Platform')
@section('header_title', 'Currency Exchange Rate Risk')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Nilai Tukar Mata Uang & Fluktuasi Valuta</h5>
                    <p class="text-muted small mb-0">Mendeteksi risiko finansial akibat fluktuasi mata uang asing terhadap stabilitas harga logistik global.</p>
                </div>
                <button id="btn-refresh-currency" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> Cek Kurs
                </button>
            </div>
            
            <div class="alert alert-light border border-light-subtle rounded-3 p-4">
                <div class="text-center text-muted">
                    <i class="bi bi-cash-coin fs-2 mb-2 d-block"></i>
                    <p class="mb-0">Fitur nilai tukar mata uang disiapkan untuk terintegrasi dengan Exchange Rate API pada tahap berikutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('btn-refresh-currency').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        
        try {
            const response = await SupplyChainAPI.fetch('currency');
            alert('AJAX Endpoint connected: ' + response.message);
        } catch (error) {
            alert('Error fetching Currency API: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Cek Kurs';
        }
    });
</script>
@endsection
