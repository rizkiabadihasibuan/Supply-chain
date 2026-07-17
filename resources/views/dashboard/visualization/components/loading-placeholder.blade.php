{{-- ============================================================
     LOADING PLACEHOLDER SKELETON
     resources/views/dashboard/visualization/components/loading-placeholder.blade.php
     Skeleton shapes mimic the real layout: 6 KPI cards, 4 charts, insight panel, table
     ============================================================ --}}

<div id="skeletonSection" class="viz-fade-in">

    {{-- 6 KPI Skeleton Cards --}}
    <div class="row g-3 mb-4">
        @for ($i = 0; $i < 6; $i++)
        <div class="col-6 col-md-4 col-xl-2">
            <div class="card border-0 shadow-sm p-4 rounded-4">
                <div class="d-flex justify-content-between mb-3">
                    <div class="placeholder-glow" style="width:48px;height:48px;border-radius:12px;background:#E2E8F0;overflow:hidden;">
                        <span class="placeholder w-100 h-100"></span>
                    </div>
                    <span class="placeholder col-4 rounded-pill" style="height:22px;"></span>
                </div>
                <span class="placeholder col-6 mb-2 d-block" style="height:12px;border-radius:6px;"></span>
                <span class="placeholder col-9 mb-2 d-block" style="height:28px;border-radius:8px;"></span>
                <span class="placeholder col-5 d-block" style="height:11px;border-radius:6px;"></span>
            </div>
        </div>
        @endfor
    </div>

    <div class="row g-4">
        {{-- LEFT: 4 Chart skeletons + table skeleton --}}
        <div class="col-12 col-lg-8">
            {{-- 2x2 chart grid --}}
            <div class="row g-4 mb-4">
                @for ($i = 0; $i < 4; $i++)
                <div class="col-12 col-md-6">
                    <div class="card border-0 shadow-sm p-4 rounded-4">
                        <div class="d-flex justify-content-between mb-3">
                            <div>
                                <span class="placeholder col-6 d-block mb-2" style="height:14px;border-radius:6px;"></span>
                                <span class="placeholder col-9 d-block" style="height:10px;border-radius:6px;"></span>
                            </div>
                            <div class="d-flex gap-1">
                                <span class="placeholder" style="width:28px;height:28px;border-radius:8px;"></span>
                                <span class="placeholder" style="width:28px;height:28px;border-radius:8px;"></span>
                            </div>
                        </div>
                        <div class="placeholder-glow" style="height:200px;border-radius:12px;background:#F1F5F9;overflow:hidden;">
                            <span class="placeholder w-100 h-100"></span>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            {{-- Table skeleton --}}
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="p-4 border-bottom" style="background:#FAFCFF;">
                    <div class="d-flex justify-content-between">
                        <span class="placeholder col-3 d-block" style="height:16px;border-radius:6px;"></span>
                        <span class="placeholder col-2 d-block" style="height:16px;border-radius:6px;"></span>
                    </div>
                </div>
                <div class="p-0">
                    <table class="table mb-0">
                        <thead style="background:#F8FAFC;">
                            <tr>
                                @for($c = 0; $c < 7; $c++)
                                <th class="py-3"><span class="placeholder col-{{ rand(5,9) }}" style="height:10px;border-radius:4px;"></span></th>
                                @endfor
                            </tr>
                        </thead>
                        <tbody>
                            @for ($r = 0; $r < 5; $r++)
                            <tr>
                                @for($c = 0; $c < 7; $c++)
                                <td class="py-3"><span class="placeholder col-{{ rand(5,10) }}" style="height:12px;border-radius:4px;"></span></td>
                                @endfor
                            </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- RIGHT: Insight panel skeleton --}}
        <div class="col-12 col-lg-4">
            @for ($i = 0; $i < 7; $i++)
            <div class="card border-0 shadow-sm p-4 rounded-4 mb-3">
                <div class="d-flex justify-content-between mb-3">
                    <span class="placeholder col-5 d-block" style="height:13px;border-radius:6px;"></span>
                    <span class="placeholder col-3 rounded-pill d-block" style="height:20px;"></span>
                </div>
                <span class="placeholder col-8 d-block mb-2" style="height:20px;border-radius:6px;"></span>
                <span class="placeholder col-5 d-block" style="height:10px;border-radius:6px;"></span>
            </div>
            @endfor
        </div>
    </div>
</div>
