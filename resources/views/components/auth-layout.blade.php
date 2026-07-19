@props([
    'title' => 'SupplyChain Platform',
    'pageTitle' => '',
    'pageSubtitle' => '',
    'illustration' => 'default'
])

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - SupplyChain Platform</title>

    <!-- Google Font: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Auth CSS -->
    <link href="{{ asset('css/auth/auth.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth/responsive.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="auth-body">

    <div class="auth-wrapper">
        {{-- ══════ LEFT PANEL: Illustration ══════ --}}
        <div class="auth-panel-left">
            {{-- Floating decorative shapes --}}
            <div class="auth-floating-shapes">
                <div class="auth-shape auth-shape-1"></div>
                <div class="auth-shape auth-shape-2"></div>
                <div class="auth-shape auth-shape-3"></div>
            </div>

            <div class="auth-left-content" style="position: relative; z-index: 2; text-align: center; max-width: 440px;">
                {{-- Illustration SVG --}}
                @if($illustration === 'verify')
                    @include('auth.partials._illustration-verify')
                @elseif($illustration === 'forgot')
                    @include('auth.partials._illustration-forgot')
                @elseif($illustration === 'reset')
                    @include('auth.partials._illustration-reset')
                @else
                    @include('auth.partials._illustration-default')
                @endif

                {{-- Left panel text --}}
                <h2 class="text-white fw-bold mt-4 mb-2" style="font-size: 1.65rem; line-height: 1.3;">
                    Global Supply Chain<br>Risk Intelligence
                </h2>
                <p class="text-white-50" style="font-size: .92rem; line-height: 1.6; max-width: 360px; margin: 0 auto;">
                    Platform monitoring risiko rantai pasok global berbasis multi-API dan analitik data real-time.
                </p>

                {{-- Trust indicators --}}
                <div class="d-flex align-items-center justify-content-center gap-4 mt-4">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255,255,255,.12);">
                            <i class="bi bi-shield-check text-white" style="font-size: .85rem;"></i>
                        </div>
                        <span class="text-white-50" style="font-size: .78rem;">Enkripsi SSL</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255,255,255,.12);">
                            <i class="bi bi-globe2 text-white" style="font-size: .85rem;"></i>
                        </div>
                        <span class="text-white-50" style="font-size: .78rem;">195+ Negara</span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; background: rgba(255,255,255,.12);">
                            <i class="bi bi-activity text-white" style="font-size: .85rem;"></i>
                        </div>
                        <span class="text-white-50" style="font-size: .78rem;">Real-time</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- ══════ RIGHT PANEL: Form ══════ --}}
        <div class="auth-panel-right">
            <div class="auth-form-card auth-fade-in">
                {{-- Logo --}}
                <div class="auth-logo">
                    <div class="auth-logo-icon">
                        <i class="bi bi-shield-shaded"></i>
                    </div>
                    <div>
                        <div class="auth-logo-text">Supply<span style="color: var(--auth-primary);">Chain</span></div>
                        <div class="auth-logo-sub">Intelligence Platform</div>
                    </div>
                </div>

                {{-- Page title & subtitle --}}
                @if($pageTitle)
                    <h1 class="auth-title">{{ $pageTitle }}</h1>
                @endif
                @if($pageSubtitle)
                    <p class="auth-subtitle">{{ $pageSubtitle }}</p>
                @endif

                {{-- Form slot --}}
                {{ $slot }}
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Auth JS -->
    <script src="{{ asset('js/auth/auth.js') }}"></script>
    <script src="{{ asset('js/auth/validation.js') }}"></script>
    <script src="{{ asset('js/auth/animation.js') }}"></script>
    @stack('scripts')
</body>
</html>
