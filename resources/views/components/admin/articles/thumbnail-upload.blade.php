{{-- ═══════════════════════════════════════════════════
     THUMBNAIL UPLOAD COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/thumbnail-upload.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'type' => 'create'
])

<div class="mb-3">
    <label class="form-label small fw-semibold text-secondary mb-1.5" id="label-thumbnail-{{ $type }}">Upload Thumbnail</label>
    <div class="border border-2 border-dashed rounded p-3 text-center bg-light-subtle d-flex flex-column align-items-center justify-content-center" 
         style="cursor:pointer;" 
         onclick="document.getElementById('{{ $type }}-thumbnail-file').click()" 
         id="{{ $type }}-thumbnail-dropzone"
         ondragover="ArticlesUpload.handleDragOver(event)"
         ondragleave="ArticlesUpload.handleDragLeave(event)"
         ondrop="ArticlesUpload.handleDrop(event, '{{ $type }}')"
         aria-labelledby="label-thumbnail-{{ $type }}"
         tabindex="0">
        <i class="bi bi-image fs-3 text-primary mb-1"></i>
        <span class="small fw-semibold text-dark mb-0.5">Seret & lepas berkas, atau klik untuk telusuri</span>
        <span class="text-secondary" style="font-size:0.65rem;">(Format yang didukung: JPG, PNG, WEBP)</span>
        <input type="file" id="{{ $type }}-thumbnail-file" accept="image/png, image/jpeg, image/webp" class="d-none" onchange="ArticlesModal.handleThumbnailSelect(event, '{{ $type }}')">
    </div>
    <div class="d-flex align-items-center gap-2 mt-2">
        <img id="{{ $type }}-thumbnail-preview" src="#" alt="Pratinjau Gambar {{ $type }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover; display: none;">
    </div>
</div>
