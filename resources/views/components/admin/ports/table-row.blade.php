{{-- ═══════════════════════════════════════════════════
     TABLE ROW COMPONENT – Milestone 3.15C
     resources/views/components/admin/ports/table-row.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'no' => '1',
    'code' => '',
    'name' => '',
    'country' => '',
    'region' => '',
    'status' => 'Aktif',
    'latitude' => '',
    'longitude' => '',
    'lastUpdated' => '',
    'timezone' => 'GMT+07:00',
    'capacity' => 'Medium'
])

<tr>
    <td data-label="No" class="text-secondary small fw-semibold">
        {{ $no }}
    </td>
    <td data-label="Kode Pelabuhan" class="fw-bold text-dark">
        {{ $code }}
    </td>
    <td data-label="Nama Pelabuhan" class="fw-bold text-primary text-start">
        {{ $name }}
    </td>
    <td data-label="Negara" class="text-secondary small text-start">
        {{ $country }}
    </td>
    <td data-label="Wilayah (Region)" class="text-secondary small text-start">
        {{ $region }}
    </td>
    <td data-label="Latitude" class="text-secondary small">
        {{ $latitude }}
    </td>
    <td data-label="Longitude" class="text-secondary small">
        {{ $longitude }}
    </td>
    <td data-label="Status">
        @if($status === 'Aktif')
            <span class="badge badge-status-aktif">Aktif</span>
        @elseif($status === 'Tidak Aktif')
            <span class="badge badge-status-tidak-aktif">Tidak Aktif</span>
        @else
            <span class="badge badge-status-maintenance">Maintenance</span>
        @endif
    </td>
    <td data-label="Last Updated" class="text-secondary small">
        {{ $lastUpdated }}
    </td>
    <td data-label="Action">
        <x-admin.ports.action-dropdown 
            :code="$code"
            :name="$name"
            :country="$country"
            :region="$region"
            :status="$status"
            :latitude="$latitude"
            :longitude="$longitude"
            :lastSync="$lastUpdated"
            :timezone="$timezone"
            :capacity="$capacity"
        />
    </td>
</tr>
