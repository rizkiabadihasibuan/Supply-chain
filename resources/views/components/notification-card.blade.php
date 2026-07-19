{{-- ═══════════════════════════════════════════════════
     NOTIFICATION CARD COMPONENT – Milestone 3.15A
     resources/views/components/notification-card.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'notifications' => [
        ['id' => 1, 'title' => 'API Exchange Rate melambat', 'time' => '3 menit lalu', 'icon' => 'exclamation-triangle-fill', 'color' => 'warning'],
        ['id' => 2, 'title' => 'User baru terdaftar: John Doe', 'time' => '15 menit lalu', 'icon' => 'person-plus-fill', 'color' => 'primary'],
        ['id' => 3, 'title' => 'API GNews sempat Offline', 'time' => '1 jam lalu', 'icon' => 'wifi-off', 'color' => 'danger'],
        ['id' => 4, 'title' => 'Backup database mingguan selesai', 'time' => '3 jam lalu', 'icon' => 'database-check', 'color' => 'success'],
        ['id' => 5, 'title' => 'Umpan cuaca ekstrem pelabuhan dimuat', 'time' => '5 jam lalu', 'icon' => 'cloud-lightning-rain-fill', 'color' => 'info']
    ]
])

<div class="card p-4 border-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark mb-0">Notifikasi Sistem Terbaru</h6>
        <span class="text-secondary small" style="font-size:0.75rem;">5 Alert</span>
    </div>
    
    <div class="list-group list-group-flush" id="admin-notification-list">
        @foreach($notifications as $notif)
            <div class="list-group-item px-0 py-2.5 bg-transparent border-0 d-flex gap-2.5 align-items-start">
                <div class="rounded-3 p-1.5 d-flex align-items-center justify-content-center bg-{{ $notif['color'] }} bg-opacity-10 text-{{ $notif['color'] }}" style="width: 32px; height: 32px; flex-shrink: 0;">
                    <i class="bi bi-{{ $notif['icon'] }}" style="font-size: 0.9rem;"></i>
                </div>
                <div class="flex-grow-1">
                    <span class="d-block text-dark fw-semibold" style="font-size: 0.8rem; line-height: 1.2;">{{ $notif['title'] }}</span>
                    <span class="text-secondary" style="font-size: 0.65rem;">{{ $notif['time'] }}</span>
                </div>
                <button type="button" class="btn-close ms-auto" style="font-size:0.6rem; padding:0.25rem;" aria-label="Dismiss" onclick="AdminNotification.dismissNotification(this)"></button>
            </div>
        @endforeach
    </div>
</div>
