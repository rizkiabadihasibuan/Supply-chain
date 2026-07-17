@props([
    'placeholder' => 'Cari...',
    'searchId' => 'search-input',
    'oninput' => ''
])

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card p-4 border-0">
            <div class="row g-3 align-items-center">
                <!-- Search Bar -->
                <div class="col-xl-4 col-lg-3 col-md-12 col-12">
                    <div class="search-wrapper w-100">
                        <i class="bi bi-search"></i>
                        <input type="text" id="{{ $searchId }}" placeholder="{{ $placeholder }}" class="form-control ps-5 w-100" style="min-height: 44px;" @if($oninput) oninput="{{ $oninput }}" @endif>
                    </div>
                </div>

                <!-- Custom Filter Slots -->
                @if(isset($filters))
                    {{ $filters }}
                @endif
            </div>

            <!-- Custom Simulation Action Slots -->
            @if(isset($simulations))
                <div class="d-flex flex-wrap gap-2 mt-3 pt-3 border-top">
                    {{ $simulations }}
                </div>
            @endif
        </div>
    </div>
</div>
