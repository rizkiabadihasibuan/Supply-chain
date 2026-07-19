{{-- ═══════════════════════════════════════════════════
     ARTICLE ROW COMPONENT – Milestone 3.15D
     resources/views/components/admin/articles/article-row.blade.php
     ═══════════════════════════════════════════════════ --}}

@props([
    'no' => '1',
    'title' => '',
    'category' => '',
    'status' => 'Published',
    'author' => 'Administrator',
    'publishedAt' => '',
    'views' => '0',
    'thumbnail' => '',
    'content' => ''
])

@php
    $defaultThumbnail = 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?q=80&w=150&auto=format&fit=crop';
    $activeThumbnail = $thumbnail ?: $defaultThumbnail;
@endphp

<tr>
    <td data-label="No" class="text-secondary small fw-semibold">
        {{ $no }}
    </td>
    <td data-label="Thumbnail">
        <img src="{{ $activeThumbnail }}" alt="Thumbnail {{ $title }}" class="rounded" style="width: 80px; height: 60px; object-fit: cover;">
    </td>
    <td data-label="Judul Artikel" class="fw-bold text-primary text-start">
        {{ $title }}
    </td>
    <td data-label="Kategori" class="text-secondary small text-start">
        <span class="badge bg-light text-dark border">{{ $category }}</span>
    </td>
    <td data-label="Penulis" class="text-secondary small text-start">
        {{ $author }}
    </td>
    <td data-label="Tanggal Publish" class="text-secondary small">
        {{ $publishedAt }}
    </td>
    <td data-label="Status">
        @if($status === 'Published')
            <span class="badge badge-status-published">Published</span>
        @elseif($status === 'Draft')
            <span class="badge badge-status-draft">Draft</span>
        @else
            <span class="badge badge-status-archived">Archived</span>
        @endif
    </td>
    <td data-label="Views" class="text-secondary small fw-semibold">
        {{ $views }}
    </td>
    <td data-label="Action">
        <x-admin.articles.article-action-dropdown 
            :title="$title"
            :category="$category"
            :status="$status"
            :author="$author"
            :publishedAt="$publishedAt"
            :content="$content"
            :views="$views"
            :thumbnail="$activeThumbnail"
        />
    </td>
</tr>
