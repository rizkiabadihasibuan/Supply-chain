<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SupplyRisk.io - Global Supply Chain Risk Intelligence Platform</title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom-dark.css') }}">
</head>
<body class="hero-gradient">

    <!-- Landing Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-transparent py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('landing') }}">
                <span class="fs-3 fw-bold text-glow-cyan">SupplyRisk.io</span>
            </a>
            <div class="d-flex ms-auto">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-light me-2 px-4 glass-card">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light me-2 px-4 glass-card text-glow-cyan" style="border-color: rgba(56, 189, 248, 0.4);">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-primary px-4">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <header class="py-5 my-5">
        <div class="container py-5 text-center">
            <h1 class="display-3 fw-extrabold text-white mb-3" style="font-family: 'Outfit', sans-serif;">
                Mitigate Supply Chain Disruptions <br>
                <span class="text-glow-cyan">Before They Happen</span>
            </h1>
            <p class="lead text-secondary mx-auto mb-5" style="max-width: 700px;">
                Platform intelijen risiko rantai pasok global berbasis multi-API dan analitik data terintegrasi. Deteksi cuaca ekstrem, fluktuasi mata uang, sentimen konflik geopolitik, dan kemacetan pelabuhan dalam satu dashboard terpusat.
            </p>
            <div class="d-flex justify-content-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-5 py-3 fs-5">Masuk ke Ruang Kendali</a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-5 py-3 fs-5">Mulai Pantau Gratis</a>
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5 py-3 fs-5 glass-card">Lihat Dashboard</a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Key Statistics -->
    <section class="py-5">
        <div class="container">
            <div class="row text-center g-4">
                <div class="col-md-3">
                    <div class="glass-card p-4">
                        <h2 class="display-5 fw-bold text-glow-cyan">4</h2>
                        <p class="text-secondary mb-0">Negara Pilot Terpantau</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-4">
                        <h2 class="display-5 fw-bold text-glow-purple">6+</h2>
                        <p class="text-secondary mb-0">Integrasi REST API Global</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-4">
                        <h2 class="display-5 fw-bold text-glow-cyan">8+</h2>
                        <p class="text-secondary mb-0">Pelabuhan Kargo Terlacak</p>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="glass-card p-4">
                        <h2 class="display-5 fw-bold text-success">100%</h2>
                        <p class="text-secondary mb-0">Sentimen Berita Teranalisis</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-5 my-5">
        <div class="container">
            <h2 class="text-center text-white mb-5 display-5" style="font-family: 'Outfit', sans-serif;">Pilar Solusi Intelijen Risiko</h2>
            <div class="row g-4">
                <!-- Weather Feature -->
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-cloud-lightning-rain"></i>
                        </div>
                        <h4 class="text-white">Weather Intelligence</h4>
                        <p class="text-secondary">Integrasi real-time Open-Meteo API untuk melacak temperatur, curah hujan, kecepatan angin, dan probabilitas badai di rute logistik supplier.</p>
                    </div>
                </div>
                <!-- Currency Feature -->
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100">
                        <div class="feature-icon-wrapper" style="background: rgba(139, 92, 246, 0.1); border-color: rgba(139, 92, 246, 0.2); color: var(--accent-secondary);">
                            <i class="bi bi-currency-exchange"></i>
                        </div>
                        <h4 class="text-white">Forex Volatility Monitor</h4>
                        <p class="text-secondary">Visualisasi tren mata uang dan analisis fluktuasi nilai tukar real-time dari ExchangeRate API untuk estimasi harga pokok produksi.</p>
                    </div>
                </div>
                <!-- Sentiment Feature -->
                <div class="col-md-4">
                    <div class="glass-card p-4 h-100">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-chat-heart"></i>
                        </div>
                        <h4 class="text-white">Lexicon Sentiment Analysis</h4>
                        <p class="text-secondary">Deteksi dini kerusuhan atau mogok kerja di wilayah regional supplier dengan mengekstraksi dan menilai sentimen berita global GNews API.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-4 border-top border-secondary border-opacity-25 mt-5">
        <div class="container text-center">
            <p class="text-secondary mb-0">&copy; 2026 SupplyRisk.io. Dibuat sebagai Proyek Final Tugas Akhir.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle with Popper JS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
