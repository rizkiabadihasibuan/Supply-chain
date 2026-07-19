{{-- ═══════════════════════════════════════════════════
     API STATUS CARD COMPONENT – Milestone 3.15A
     resources/views/components/api-status-card.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'apis' => [
        ['name' => 'Weather API', 'status' => 'Online', 'time' => '120ms', 'color' => 'success'],
        ['name' => 'World Bank API', 'status' => 'Online', 'time' => '350ms', 'color' => 'success'],
        ['name' => 'Exchange Rate API', 'status' => 'Slow', 'time' => '1250ms', 'color' => 'warning'],
        ['name' => 'REST Countries API', 'status' => 'Online', 'time' => '90ms', 'color' => 'success'],
        ['name' => 'GNews API', 'status' => 'Offline', 'time' => '—', 'color' => 'danger'],
        ['name' => 'World Port Dataset', 'status' => 'Online', 'time' => '45ms', 'color' => 'success']
    ]
])

<div class="card p-4 border-0">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold text-dark mb-0">Integrasi API & Status Layanan</h6>
        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25" style="font-size: 0.65rem;">
            Sehat
        </span>
    </div>
    <p class="text-secondary small mb-4">Pantau konektivitas multi-API penunjang analisis rantai pasok secara real-time.</p>
    
    <div class="api-monitor-list">
        @foreach($apis as $api)
            <div class="api-monitor-item">
                <div class="d-flex align-items-center gap-2">
                    <span class="pulse-indicator bg-{{ $api['color'] }}" style="width: 8px; height: 8px; border-radius: 50%; display: inline-block;"></span>
                    <span class="api-name">{{ $api['name'] }}</span>
                </div>
                <div class="d-flex align-items-center">
                    <span class="api-response-time">{{ $api['time'] }}</span>
                    <span class="badge bg-{{ $api['color'] }} bg-opacity-10 text-{{ $api['color'] }} border border-{{ $api['color'] }} border-opacity-25" style="font-size: 0.65rem; min-width: 55px; text-align: center;">
                        {{ $api['status'] }}
                    </span>
                </div>
            </div>
        @endforeach
    </div>
</div>
