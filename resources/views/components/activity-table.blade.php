{{-- ═══════════════════════════════════════════════════
     ACTIVITY TABLE COMPONENT – Milestone 3.14
     resources/views/components/activity-table.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'logs' => [
        [
            'date' => '18-07-2026 09:42:15',
            'activity' => 'Melakukan otentikasi masuk ke dasbor utama',
            'module' => 'Authentication',
            'status' => 'Sukses',
            'ip' => '192.168.1.45',
            'device' => 'Chrome (Windows)'
        ],
        [
            'date' => '18-07-2026 09:35:44',
            'activity' => 'Membandingkan tingkat risiko mitigasi ID vs US',
            'module' => 'Comparison Engine',
            'status' => 'Sukses',
            'ip' => '192.168.1.45',
            'device' => 'Chrome (Windows)'
        ],
        [
            'date' => '17-07-2026 14:22:10',
            'activity' => 'Menambahkan pelabuhan Tanjung Priok ke Watchlist',
            'module' => 'Monitoring Center',
            'status' => 'Sukses',
            'ip' => '192.168.1.45',
            'device' => 'Chrome (Windows)'
        ],
        [
            'date' => '17-07-2026 11:05:19',
            'activity' => 'Mengubah password keamanan akun',
            'module' => 'Security settings',
            'status' => 'Sukses',
            'ip' => '192.168.1.11',
            'device' => 'Safari (iPhone)'
        ],
        [
            'date' => '16-07-2026 18:44:30',
            'activity' => 'Mengekspor laporan pdf berita cuaca logistik',
            'module' => 'Weather reports',
            'status' => 'Sukses',
            'ip' => '192.168.1.45',
            'device' => 'Chrome (Windows)'
        ]
    ]
])

<div class="card p-4 border-0">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-primary me-2"></i>Log Aktivitas Pengguna</h5>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" style="min-height:34px;" id="btn-toggle-empty-activity" onclick="toggleEmptyActivityState()">
                ⚡ Simulasikan Kosong
            </button>
            <div class="position-relative" style="width: 240px;">
                <input type="text" class="form-control form-control-sm ps-4.5" placeholder="Cari aktivitas..." style="min-height: 34px; font-size: 0.85rem;" id="activity-search-input">
                <i class="bi bi-search position-absolute text-secondary" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
            </div>
        </div>
    </div>

    {{-- Container untuk Tabel Aktivitas --}}
    <div id="activity-data-container">
        @if(count($logs) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle activity-responsive-table mb-0">
                    <thead>
                        <tr>
                            <th>Waktu & Tanggal</th>
                            <th>Aktivitas</th>
                            <th>Modul</th>
                            <th>Status</th>
                            <th>Alamat IP</th>
                            <th>Perangkat</th>
                        </tr>
                    </thead>
                    <tbody id="activity-tbody">
                        @foreach($logs as $log)
                            <tr>
                                <td data-label="Waktu & Tanggal" class="text-secondary small">{{ $log['date'] }}</td>
                                <td data-label="Aktivitas" class="fw-semibold text-dark">{{ $log['activity'] }}</td>
                                <td data-label="Modul" class="small"><span class="badge bg-light text-secondary border">{{ $log['module'] }}</span></td>
                                <td data-label="Status"><span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">{{ $log['status'] }}</span></td>
                                <td data-label="Alamat IP" class="text-secondary small">{{ $log['ip'] }}</td>
                                <td data-label="Perangkat" class="text-secondary small">{{ $log['device'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination Placeholder --}}
            <div class="d-flex justify-content-between align-items-center mt-4 flex-wrap gap-2 pt-2 border-top">
                <span class="text-secondary small">Menampilkan 1-5 dari {{ count($logs) }} entri</span>
                <nav aria-label="Page navigation example">
                    <ul class="pagination pagination-sm mb-0">
                        <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                    </ul>
                </nav>
            </div>
        @else
            <x-empty-state title="Belum ada aktivitas." description="Semua log aktivitas pengguna saat ini kosong." buttonText="" onclick="" />
        @endif
    </div>

    {{-- Container Cadangan untuk Empty State via simulasi JS --}}
    <div id="activity-empty-container" style="display: none;">
        <x-empty-state title="Belum ada aktivitas." description="Semua log aktivitas pengguna saat ini kosong." buttonText="" onclick="" />
    </div>
</div>

<script>
    // Penanganan pencarian simulasi
    document.addEventListener('DOMContentLoaded', () => {
        const searchInput = document.getElementById('activity-search-input');
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const rows = document.querySelectorAll('#activity-tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    row.style.display = text.includes(query) ? '' : 'none';
                });
            });
        }
    });

    // Simulasi Empty State
    function toggleEmptyActivityState() {
        const container = document.getElementById('activity-data-container');
        const emptyContainer = document.getElementById('activity-empty-container');
        const btn = document.getElementById('btn-toggle-empty-activity');

        if (container.style.display !== 'none') {
            container.style.display = 'none';
            emptyContainer.style.display = 'block';
            btn.textContent = '⚡ Pulihkan Data';
        } else {
            container.style.display = 'block';
            emptyContainer.style.display = 'none';
            btn.textContent = '⚡ Simulasikan Kosong';
        }
    }
</script>
