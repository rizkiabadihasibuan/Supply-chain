@props([
    'id',
    'title',
    'description',
    'priority' => 'low', // critical, high, medium, low
    'category' => 'system', // risk, weather, port, country, news, currency, system
    'country' => 'Global',
    'date' => 'Baru saja',
    'status' => 'unread' // unread, read, archived
])

@php
    $priorityColor = 'text-primary';
    $priorityBg = 'rgba(37, 99, 235, 0.08)';
    $priorityIcon = 'bi-info-circle';
    
    if ($priority === 'critical') {
        $priorityColor = 'text-danger';
        $priorityBg = 'rgba(239, 68, 68, 0.08)';
        $priorityIcon = 'bi-exclamation-octagon-fill';
    } elseif ($priority === 'high') {
        $priorityColor = 'text-warning';
        $priorityBg = 'rgba(245, 158, 11, 0.08)';
        $priorityIcon = 'bi-exclamation-triangle-fill';
    } elseif ($priority === 'medium') {
        $priorityColor = 'text-warning';
        $priorityBg = 'rgba(245, 158, 11, 0.08)';
        $priorityIcon = 'bi-bell-fill';
    }
    
    $catLabel = 'Sistem';
    if ($category === 'risk') $catLabel = 'Risiko';
    elseif ($category === 'weather') $catLabel = 'Cuaca';
    elseif ($category === 'port') $catLabel = 'Pelabuhan';
    elseif ($category === 'country') $catLabel = 'Negara';
    elseif ($category === 'news') $catLabel = 'Berita';
    elseif ($category === 'currency') $catLabel = 'Valas';
@endphp

<div class="card p-3.5 border-0 mb-3 notification-card-item h-100 @if($status === 'unread') border-start border-4 @if($priority === 'critical') border-danger @elseif($priority === 'high' || $priority === 'medium') border-warning @else border-primary @endif @endif" 
     id="notification-card-{{ $id }}" 
     data-id="{{ $id }}" 
     data-title="{{ $title }}" 
     data-description="{{ $description }}" 
     data-priority="{{ $priority }}" 
     data-category="{{ $category }}" 
     data-country="{{ $country }}" 
     data-date="{{ $date }}" 
     data-status="{{ $status }}"
     style="cursor: pointer; background-color: @if($status === 'unread') #FFFFFF @else #F8FAFC @endif;"
     onclick="selectNotification('{{ $id }}')">
     
    <div class="d-flex gap-3 align-items-start">
        <div class="p-2.5 rounded-circle d-flex align-items-center justify-content-center" style="background-color: {{ $priorityBg }}; color: {{ $priorityColor }}; width: 40px; height: 40px; flex-shrink: 0;">
            <i class="bi {{ $priorityIcon }} fs-5"></i>
        </div>
        
        <div class="flex-grow-1">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-1 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-light text-secondary border" style="font-size: 0.65rem;">{{ $catLabel }}</span>
                    <span class="text-secondary" style="font-size: 0.725rem;"><i class="bi bi-geo-alt me-1 text-primary"></i>{{ $country }}</span>
                </div>
                <span class="text-secondary small" style="font-size: 0.7rem;">{{ $date }}</span>
            </div>
            
            <h6 class="fw-bold text-dark mb-1 notification-title-text" style="font-size: 0.925rem; line-height: 1.4; @if($status === 'read') font-weight: 500 !important; color: #64748B !important; @endif">{{ $title }}</h6>
            <p class="text-secondary small mb-3 text-truncate" style="max-width: 460px;">{{ $description }}</p>
            
            <div class="d-flex justify-content-between align-items-center">
                <span class="badge @if($status === 'unread') badge-danger @else badge-success @endif" id="status-badge-{{ $id }}" style="font-size: 0.625rem;">
                    {{ $status === 'unread' ? 'Belum Dibaca' : 'Sudah Dibaca' }}
                </span>
                
                <div class="d-flex gap-2" onclick="event.stopPropagation()">
                    <button class="btn btn-light btn-sm border py-1 px-2.5 d-flex align-items-center justify-content-center mark-read-btn" style="min-height: 38px;" onclick="toggleRead('{{ $id }}')" title="Tandai Sudah Dibaca">
                        <i class="bi @if($status === 'unread') bi-check-circle-fill text-success @else bi-envelope-fill text-primary @endif fs-6"></i>
                    </button>
                    <button class="btn btn-light btn-sm border py-1 px-2.5 d-flex align-items-center justify-content-center" style="min-height: 38px;" onclick="archiveNotification('{{ $id }}')" title="Arsipkan Notifikasi">
                        <i class="bi bi-archive-fill text-secondary fs-6"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-card-item {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .notification-card-item:hover {
        transform: translateY(-1.5px);
        box-shadow: 0 8px 20px rgba(18, 52, 88, 0.06) !important;
    }
</style>
