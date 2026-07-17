@props([
    'title',
    'subtitle' => '',
    'breadcrumbs' => []
])

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card p-4 border-0">
            @if(!empty($breadcrumbs))
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        @foreach($breadcrumbs as $label => $link)
                            @if($loop->last)
                                <li class="breadcrumb-item active" aria-current="page">{{ $label }}</li>
                            @else
                                <li class="breadcrumb-item"><a href="{{ $link }}">{{ $label }}</a></li>
                            @endif
                        @endforeach
                    </ol>
                </nav>
            @endif
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h3 class="fw-bold text-dark mb-1">{{ $title }}</h3>
                    @if($subtitle)
                        <p class="text-secondary small mb-0">{{ $subtitle }}</p>
                    @endif
                </div>
                @if(isset($actions))
                    <div class="d-flex gap-2">
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
