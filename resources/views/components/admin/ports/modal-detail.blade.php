{{-- ═══════════════════════════════════════════════════
     PORT MODAL DETAIL COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/modal-detail.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="modal fade" id="portDetailModal" tabindex="-1" aria-labelledby="portDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            {{-- Modal Header --}}
            <div class="modal-header">
                <h6 class="modal-title fw-bold text-dark" id="portDetailModalLabel"><i class="bi bi-info-circle-fill text-primary me-2"></i>Detail Pelabuhan</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            {{-- Modal Body --}}
            <div class="modal-body p-4">
                <div class="row g-4">
                    {{-- Detail Information --}}
                    <div class="col-12 col-md-6">
                        <div class="border rounded-3 p-3 bg-light-subtle">
                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Kode Pelabuhan</span>
                                <span class="port-detail-value fw-bold text-dark" id="detail-port-code">Code</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Nama Pelabuhan</span>
                                <span class="port-detail-value text-primary" id="detail-port-name">Name</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Negara</span>
                                <span class="port-detail-value" id="detail-port-country">Country</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Wilayah (Region)</span>
                                <span class="port-detail-value" id="detail-port-region">Region</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Latitude</span>
                                <span class="port-detail-value" id="detail-port-coords">Coords</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Longitude</span>
                                <span class="port-detail-value" id="detail-port-capacity">Capacity</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Timezone</span>
                                <span class="port-detail-value" id="detail-port-timezone">GMT+07:00</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">UN/LOCODE</span>
                                <span class="port-detail-value fw-bold text-dark" id="detail-port-unlocode">UN/LOCODE</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Status</span>
                                <span class="badge" id="detail-port-status">Status</span>
                            </div>

                            <div class="port-detail-row d-flex justify-content-between align-items-center">
                                <span class="port-detail-label">Tanggal Sinkronisasi Terakhir</span>
                                <span class="port-detail-value text-secondary" id="detail-port-lastsync">Last Sync</span>
                            </div>
                        </div>
                    </div>

                    {{-- Map Preview --}}
                    <div class="col-12 col-md-6">
                        <div class="card border border-light-subtle rounded-3 p-3 bg-light h-100 text-center d-flex flex-column justify-content-center align-items-center">
                            <span class="d-block small fw-bold text-secondary mb-3"><i class="bi bi-geo-alt-fill text-danger me-1"></i>Lokasi Pelabuhan</span>
                            <div class="p-3 border rounded border-2 border-dashed bg-white w-100 flex-grow-1 d-flex flex-column justify-content-center align-items-center">
                                <i class="bi bi-map-fill fs-1 text-secondary mb-2"></i>
                                <span class="text-secondary" style="font-size:0.75rem;">Peta akan ditampilkan setelah integrasi OpenStreetMap selesai.</span>
                            </div>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const originalShowDetail = PortsModal.showDetail;
        PortsModal.showDetail = function(name, code, country, region, latitude, longitude, status, capacity, lastSync) {
            originalShowDetail(name, code, country, region, latitude, longitude, status, capacity, lastSync);
            document.getElementById('detail-port-unlocode').textContent = code;
            document.getElementById('detail-port-timezone').textContent = country === 'Indonesia' ? 'Asia/Jakarta (GMT+07:00)' : 'Asia/Singapore (GMT+08:00)';
        };
    });
</script>
