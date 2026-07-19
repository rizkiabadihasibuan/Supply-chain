{{-- ═══════════════════════════════════════════════════
     ACTION DROPDOWN COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/action-dropdown.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'code' => '',
    'name' => '',
    'country' => '',
    'region' => '',
    'status' => '',
    'latitude' => '',
    'longitude' => '',
    'lastSync' => '',
    'timezone' => 'GMT+07:00',
    'capacity' => ''
])

<div class="dropdown">
    <button class="btn btn-light border btn-sm dropdown-toggle btn-action-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Aksi dataset {{ $name }}" title="Pilihan aksi dataset pelabuhan">
        Aksi
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light-subtle">
        {{-- Lihat Detail --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#portDetailModal"
                onclick="PortsModal.showDetail('{{ $name }}', '{{ $code }}', '{{ $country }}', '{{ $region }}', '{{ $latitude }}', '{{ $longitude }}', '{{ $status }}', '{{ $capacity }}', '{{ $lastSync }}')">
                <i class="bi bi-eye text-primary"></i> Lihat Detail
            </button>
        </li>
        {{-- Edit Dataset --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#portEditModal"
                onclick="PortsModal.showEdit('{{ $name }}', '{{ $code }}', '{{ $country }}', '{{ $region }}', '{{ $latitude }}', '{{ $longitude }}', '{{ $status }}', '{{ $capacity }}')">
                <i class="bi bi-pencil text-warning"></i> Edit Dataset
            </button>
        </li>
        {{-- Hapus Dataset --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2 text-danger" type="button" data-bs-toggle="modal" data-bs-target="#portDeleteModal">
                <i class="bi bi-trash-fill"></i> Hapus Dataset
            </button>
        </li>
        <li><hr class="dropdown-divider"></li>
        {{-- Lihat Lokasi --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" onclick="PortsCore.showToast('Membuka peninjau lokasi pelabuhan {{ $name }} di satelit map (Simulasi)...')">
                <i class="bi bi-geo-alt-fill text-secondary"></i> Lihat Lokasi
            </button>
        </li>
    </ul>
</div>
