{{-- ═══════════════════════════════════════════════════
     RECENT ACTIVITY TABLE COMPONENT – Milestone 3.15A
     resources/views/components/recent-activity-table.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'activities' => [
        ['time' => '09:54', 'admin' => 'Admin Utama', 'module' => 'Authentication', 'action' => 'Login Admin Panel', 'status' => 'Success', 'color' => 'success'],
        ['time' => '09:42', 'admin' => 'Admin Utama', 'module' => 'User Management', 'action' => 'Simulasi ubah data profil', 'status' => 'Success', 'color' => 'success'],
        ['time' => '09:20', 'admin' => 'Sistem Cron', 'module' => 'Ports API', 'action' => 'Sinkronisasi otomatis dataset pelabuhan', 'status' => 'Success', 'color' => 'success'],
        ['time' => '08:45', 'admin' => 'Admin Utama', 'module' => 'Authentication', 'action' => 'Login', 'status' => 'Success', 'color' => 'success'],
        ['time' => '07:15', 'admin' => 'Sistem Cron', 'module' => 'Weather API', 'action' => 'Sinkronisasi cuaca pelabuhan', 'status' => 'Failed', 'color' => 'danger']
    ]
])

<div class="card p-4 border-0">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h6 class="fw-bold text-dark mb-0">Log Aktivitas Admin Terbaru</h6>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" style="min-height: 34px; font-size: 0.8rem;" id="btn-toggle-activity-empty" onclick="AdminActivity.toggleEmptyState()">
                ⚡ Simulasikan Kosong
            </button>
            <div class="position-relative" style="width: 200px;">
                <input type="text" class="form-control form-control-sm ps-4.5" placeholder="Cari log..." style="min-height: 34px; font-size: 0.85rem;" id="admin-activity-search">
                <i class="bi bi-search position-absolute text-secondary" style="left: 12px; top: 50%; transform: translateY(-50%); font-size: 0.85rem;"></i>
            </div>
        </div>
    </div>

    {{-- Wrapper Tabel Log Aktivitas --}}
    <div id="admin-activity-table-wrapper">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 text-start">
                <thead>
                    <tr>
                        <th>Waktu</th>
                        <th>Administrator</th>
                        <th>Modul</th>
                        <th>Aksi Aktivitas</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="admin-activity-tbody">
                    @foreach($activities as $activity)
                        <tr>
                            <td class="text-secondary small">{{ $activity['time'] }}</td>
                            <td class="fw-bold text-dark">{{ $activity['admin'] }}</td>
                            <td><span class="badge bg-light text-secondary border">{{ $activity['module'] }}</span></td>
                            <td class="text-dark small">{{ $activity['action'] }}</td>
                            <td>
                                <span class="badge bg-{{ $activity['color'] }} bg-opacity-10 text-{{ $activity['color'] }} border border-{{ $activity['color'] }} border-opacity-25" style="font-size: 0.65rem;">
                                    {{ $activity['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination Placeholder --}}
        <div class="d-flex justify-content-between align-items-center mt-3 pt-2 border-top">
            <span class="text-secondary small">Menampilkan 5 entri terakhir</span>
            <nav aria-label="Page navigation">
                <ul class="pagination pagination-sm mb-0">
                    <li class="page-item disabled"><a class="page-link" href="#"><i class="bi bi-chevron-left"></i></a></li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#"><i class="bi bi-chevron-right"></i></a></li>
                </ul>
            </nav>
        </div>
    </div>

    {{-- Area Empty State (Simulasi/Sembunyi by default) --}}
    <div id="admin-activity-empty-state" style="display: none;">
        <x-empty-state 
            title="Belum ada data." 
            description="Silakan sinkronisasi data log aktivitas sistem." 
            buttonText="Refresh Data" 
            onclick="AdminDashboard.refreshDashboard()" 
        />
    </div>
</div>
