@extends('layouts.app')

@section('title', 'Countries - SupplyChain Platform')
@section('header_title', 'Countries Monitoring')

@section('content')
<div class="row g-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm p-4 bg-white rounded-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Daftar Negara</h5>
                    <p class="text-muted small mb-0">Pemantauan risiko ekonomi, kestabilan mata uang, dan indikator makro negara.</p>
                </div>
                <button id="btn-refresh" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-arrow-clockwise"></i> Refresh Data
                </button>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Negara</th>
                            <th>Kode ISO</th>
                            <th>Risiko Cuaca</th>
                            <th>Risiko Inflasi</th>
                            <th>Risiko Valuta</th>
                            <th>Risiko Politik</th>
                            <th>Total Risiko</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="countries-table-body">
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                <i class="bi bi-inbox fs-4 d-block mb-2"></i> Belum ada data. Silakan lakukan sinkronisasi data pada tahap berikutnya.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.getElementById('btn-refresh').addEventListener('click', async function() {
        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...';
        
        try {
            const response = await SupplyChainAPI.fetch('countries');
            console.log('AJAX Response for /api/countries:', response);
            alert('AJAX Endpoint connected: ' + response.message);
        } catch (error) {
            alert('Error fetching API: ' + error.message);
        } finally {
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-arrow-clockwise"></i> Refresh Data';
        }
    });
</script>
@endsection
