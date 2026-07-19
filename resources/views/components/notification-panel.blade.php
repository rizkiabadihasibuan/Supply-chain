{{-- ═══════════════════════════════════════════════════
     NOTIFICATION PANEL COMPONENT – Milestone 3.14
     resources/views/components/notification-panel.blade.php
     ═══════════════════════════════════════════════════ --}}

<div class="card p-4 border-0">
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bell-fill text-primary me-2"></i>Preferensi Notifikasi & Alert</h5>
    <p class="text-secondary small mb-4">Tentukan jenis perubahan jalur maritim dan radar risiko yang ingin dikirimkan via email atau alert sistem.</p>

    <form id="form-notification">
        <div class="d-flex flex-column gap-3">
            {{-- Email Notification --}}
            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #FAFCFF !important;">
                <div>
                    <span class="fw-semibold text-dark d-block" style="font-size: 0.875rem;">Notifikasi Email</span>
                    <span class="text-secondary small">Kirim ringkasan harian analisis risiko ke email terdaftar.</span>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="email_notify" checked style="width: 44px; height: 22px; cursor: pointer;">
                </div>
            </div>

            {{-- News Notification --}}
            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #FAFCFF !important;">
                <div>
                    <span class="fw-semibold text-dark d-block" style="font-size: 0.875rem;">Umpan Berita Logistik</span>
                    <span class="text-secondary small">Kirim info artikel penting mengenai hambatan rute logistik maritim.</span>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="news_notify" checked style="width: 44px; height: 22px; cursor: pointer;">
                </div>
            </div>

            {{-- Risk Alert --}}
            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #FAFCFF !important;">
                <div>
                    <span class="fw-semibold text-dark d-block" style="font-size: 0.875rem;">Peringatan Risiko Kritis</span>
                    <span class="text-secondary small">Aktifkan alarm otomatis jika terjadi lonjakan risiko kritis pada pelabuhan pantau.</span>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="risk_alert" checked style="width: 44px; height: 22px; cursor: pointer;">
                </div>
            </div>

            {{-- Weather Alert --}}
            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #FAFCFF !important;">
                <div>
                    <span class="fw-semibold text-dark d-block" style="font-size: 0.875rem;">Laporan Cuaca Ekstrem</span>
                    <span class="text-secondary small">Notifikasi darurat cuaca buruk pelabuhan / badai laut di rute terpilih.</span>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="weather_alert" checked style="width: 44px; height: 22px; cursor: pointer;">
                </div>
            </div>

            {{-- Currency Alert --}}
            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #FAFCFF !important;">
                <div>
                    <span class="fw-semibold text-dark d-block" style="font-size: 0.875rem;">Pemberitahuan Volatilitas Kurs Valuta</span>
                    <span class="text-secondary small">Alert jika fluktuasi nilai tukar base currency melebihi batas toleransi.</span>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="currency_alert" style="width: 44px; height: 22px; cursor: pointer;">
                </div>
            </div>

            {{-- System Notification --}}
            <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #FAFCFF !important;">
                <div>
                    <span class="fw-semibold text-dark d-block" style="font-size: 0.875rem;">Pemeliharaan & Update Sistem</span>
                    <span class="text-secondary small">Kirim pengumuman rilis fitur baru atau jadwal maintenance server.</span>
                </div>
                <div class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" role="switch" id="system_notify" checked style="width: 44px; height: 22px; cursor: pointer;">
                </div>
            </div>
        </div>

        <div class="mt-4 pt-3 border-top">
            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Simpan Preferensi</button>
        </div>
    </form>
</div>
