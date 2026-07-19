{{-- ═══════════════════════════════════════════════════
     SUCCESS ALERT COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/success-alert.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'message' => 'Tindakan berhasil diselesaikan.'
])

<div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 p-3.5 text-start" role="alert">
    <i class="bi bi-check-circle-fill fs-5"></i>
    <div id="articles-success-alert-text">{{ $message }}</div>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="padding: 1.15rem;"></button>
</div>
