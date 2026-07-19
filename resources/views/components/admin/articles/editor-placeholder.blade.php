{{-- ═══════════════════════════════════════════════════
     EDITOR PLACEHOLDER COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/editor-placeholder.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'type' => 'create',
    'placeholder' => 'Mulai menulis konten analisis rantai pasok di sini...',
    'required' => true
])

<div class="mb-3">
    <label for="{{ $type }}-article-content" class="form-label small fw-semibold text-secondary mb-1.5" id="label-editor-{{ $type }}">Isi Artikel (Rich Text Editor)</label>
    
    {{-- Editor Toolbar Placeholder --}}
    <div class="btn-toolbar mb-2 bg-light border p-2 rounded d-flex gap-1" role="toolbar" aria-label="Toolbar editor artikel">
        <div class="btn-group btn-group-sm me-2" role="group">
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('bold')" title="Tebal" aria-label="Tebal"><i class="bi bi-type-bold"></i></button>
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('italic')" title="Miring" aria-label="Miring"><i class="bi bi-type-italic"></i></button>
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('underline')" title="Garis Bawah" aria-label="Garis Bawah"><i class="bi bi-type-underline"></i></button>
        </div>
        <div class="btn-group btn-group-sm me-2" role="group">
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('heading')" title="Heading" aria-label="Heading"><i class="bi bi-type-h1"></i></button>
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('bullet-list')" title="Bullet List" aria-label="Bullet List"><i class="bi bi-list-task"></i></button>
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('number-list')" title="Number List" aria-label="Number List"><i class="bi bi-list-ol"></i></button>
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('quote')" title="Kutipan" aria-label="Kutipan"><i class="bi bi-quote"></i></button>
        </div>
        <div class="btn-group btn-group-sm" role="group">
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('link')" title="Sisipkan Link" aria-label="Sisipkan Link"><i class="bi bi-link-45deg"></i></button>
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('image')" title="Sisipkan Gambar" aria-label="Sisipkan Gambar"><i class="bi bi-image"></i></button>
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('table')" title="Sisipkan Tabel" aria-label="Sisipkan Tabel"><i class="bi bi-table"></i></button>
            <button type="button" class="btn btn-outline-secondary" onclick="ArticlesEditor.triggerFormat('code-block')" title="Code Block" aria-label="Code Block"><i class="bi bi-code-square"></i></button>
        </div>
    </div>

    <textarea id="{{ $type }}-article-content" 
              class="form-control notion-editor-preview" 
              rows="6" 
              placeholder="{{ $placeholder }}" 
              {{ $required ? 'required' : '' }} 
              style="resize: vertical;"
              aria-labelledby="label-editor-{{ $type }}"></textarea>
    <div class="invalid-feedback">Isi artikel wajib diisi.</div>
</div>
