@props([
    'type' => 'card', // card, table
    'count' => 4,
    'height' => '200px'
])

<div class="row g-4 mb-4">
    @for($i = 0; $i < $count; $i++)
        <div class="col-12 @if($type === 'card') col-md-6 col-lg-3 @endif">
            <div class="card p-4 border-0 skeleton-card" style="height: {{ $height }};">
                @if($type === 'table')
                    <div class="skeleton-shimmer mb-3" style="width: 25%; height: 20px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer mb-2" style="width: 100%; height: 16px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer mb-2" style="width: 95%; height: 16px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer" style="width: 80%; height: 16px; border-radius: 4px;"></div>
                @else
                    <div class="skeleton-shimmer mb-3" style="width: 45px; height: 30px; border-radius: 6px;"></div>
                    <div class="skeleton-shimmer mb-2" style="width: 70%; height: 18px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer mb-4" style="width: 40%; height: 12px; border-radius: 4px;"></div>
                    <div class="skeleton-shimmer mt-auto" style="width: 100%; height: 44px; border-radius: 10px;"></div>
                @endif
            </div>
        </div>
    @endfor
</div>

<style>
    .skeleton-card {
        background-color: #FFFFFF;
        position: relative;
        overflow: hidden;
        border: 1px solid var(--border-color) !important;
        border-radius: var(--radius-custom) !important;
    }
    .skeleton-shimmer {
        background: #F1F5F9;
        background-image: linear-gradient(to right, #F1F5F9 0%, #E2E8F0 20%, #F1F5F9 40%, #F1F5F9 100%);
        background-repeat: no-repeat;
        background-size: 800px 100%;
        display: inline-block;
        position: relative;
        animation: shimmer-animation 1.5s linear infinite forwards;
    }
    @keyframes shimmer-animation {
        0% { background-position: -468px 0; }
        100% { background-position: 468px 0; }
    }
</style>
