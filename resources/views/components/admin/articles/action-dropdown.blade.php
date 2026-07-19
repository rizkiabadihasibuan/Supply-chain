{{-- ═══════════════════════════════════════════════════
     ACTION DROPDOWN COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/action-dropdown.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'title' => '',
    'category' => '',
    'status' => '',
    'author' => '',
    'publishedAt' => '',
    'content' => '',
    'views' => '0',
    'thumbnail' => ''
])

<div class="dropdown">
    <button class="btn btn-light border btn-sm dropdown-toggle btn-action-trigger" type="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Aksi artikel {{ $title }}" title="Pilihan aksi artikel">
        Aksi
    </button>
    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-light-subtle">
        {{-- Lihat Artikel --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#articleDetailModal"
                onclick="ArticlesModal.showDetail('{{ $title }}', '{{ $category }}', '{{ $status }}', '{{ $author }}', '{{ $publishedAt }}', '{{ $content }}', '{{ $views }}', '{{ $thumbnail }}')">
                <i class="bi bi-eye text-primary"></i> Lihat Artikel
            </button>
        </li>
        {{-- Edit Artikel --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" data-bs-toggle="modal" data-bs-target="#articleEditModal"
                onclick="ArticlesModal.showEdit('{{ $title }}', '{{ $category }}', '{{ $status }}', '{{ $content }}', '{{ $publishedAt }}', '{{ $thumbnail }}')">
                <i class="bi bi-pencil text-warning"></i> Edit Artikel
            </button>
        </li>
        {{-- Duplikat Artikel --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" onclick="ArticlesModal.duplicateArticle('{{ $title }}')">
                <i class="bi bi-file-earmark-plus text-success"></i> Duplikat Artikel
            </button>
        </li>
        {{-- Archive Artikel --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2" type="button" onclick="ArticlesModal.archiveArticle('{{ $title }}')">
                <i class="bi bi-archive text-secondary"></i> Archive Artikel
            </button>
        </li>
        {{-- Hapus Artikel --}}
        <li>
            <button class="dropdown-item d-flex align-items-center gap-2 text-danger" type="button" data-bs-toggle="modal" data-bs-target="#articleDeleteModal">
                <i class="bi bi-trash-fill"></i> Hapus Artikel
            </button>
        </li>
    </ul>
</div>
