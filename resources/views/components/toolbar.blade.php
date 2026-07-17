@props([
    'simulations' => ''
])

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card p-4 border-0">
            <div class="row g-3 align-items-center">
                {{ $slot }}
            </div>
            @if(isset($simulations))
                <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                    {{ $simulations }}
                </div>
            @endif
        </div>
    </div>
</div>
