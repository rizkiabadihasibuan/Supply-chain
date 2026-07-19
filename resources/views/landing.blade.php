<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="SupplyChain - Platform monitoring risiko rantai pasok global berbasis multi-API dan analitik data real-time.">
    <title>SupplyChain – Global Supply Chain Risk Intelligence Platform</title>

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root {
            --lp-primary: #2563EB;
            --lp-primary-dark: #1D4ED8;
            --lp-primary-light: #3B82F6;
            --lp-secondary: #0F172A;
            --lp-accent: #06B6D4;
            --lp-success: #22C55E;
            --lp-warning: #F59E0B;
            --lp-danger: #EF4444;
            --lp-bg: #F8FAFC;
            --lp-card: #FFFFFF;
            --lp-text: #1E293B;
            --lp-muted: #64748B;
            --lp-border: #E2E8F0;
            --lp-gradient: linear-gradient(135deg, #0F172A 0%, #1E3A5F 50%, #2563EB 100%);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; color: var(--lp-text); overflow-x: hidden; }

        /* ═══ NAVBAR ═══ */
        .lp-navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 1000;
            padding: 1rem 0;
            transition: all .3s ease;
            background: transparent;
        }
        .lp-navbar.scrolled {
            background: rgba(15,23,42,.95);
            backdrop-filter: blur(20px);
            padding: .7rem 0;
            box-shadow: 0 4px 30px rgba(0,0,0,.15);
        }
        .lp-nav-brand {
            font-weight: 800; font-size: 1.35rem; color: #fff; text-decoration: none;
            display: flex; align-items: center; gap: .5rem;
        }
        .lp-nav-brand i { color: var(--lp-primary-light); font-size: 1.4rem; }
        .lp-nav-brand span { color: var(--lp-primary-light); }
        .lp-nav-links { display: flex; gap: 2rem; list-style: none; margin: 0; padding: 0; }
        .lp-nav-links a {
            color: rgba(255,255,255,.75); text-decoration: none; font-size: .9rem; font-weight: 500;
            transition: color .2s; position: relative;
        }
        .lp-nav-links a:hover { color: #fff; }
        .lp-nav-links a::after {
            content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px;
            background: var(--lp-primary-light); transition: width .2s;
        }
        .lp-nav-links a:hover::after { width: 100%; }
        .lp-btn-outline {
            border: 1.5px solid rgba(255,255,255,.3); color: #fff; padding: .5rem 1.5rem;
            border-radius: 8px; text-decoration: none; font-size: .88rem; font-weight: 600;
            transition: all .2s;
        }
        .lp-btn-outline:hover { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.6); }
        .lp-btn-primary-nav {
            background: var(--lp-primary); color: #fff; padding: .5rem 1.5rem;
            border-radius: 8px; text-decoration: none; font-size: .88rem; font-weight: 600;
            border: none; transition: all .2s;
        }
        .lp-btn-primary-nav:hover { background: var(--lp-primary-dark); color: #fff; transform: translateY(-1px); }

        /* ═══ HERO ═══ */
        .lp-hero {
            background: var(--lp-gradient);
            min-height: 100vh; display: flex; align-items: center;
            position: relative; overflow: hidden;
        }
        .lp-hero::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .lp-hero-floating {
            position: absolute; border-radius: 50%; opacity: .08;
            background: white; animation: floatUp 20s infinite ease-in-out;
        }
        .lp-hero-floating:nth-child(1) { width: 300px; height: 300px; top: -50px; right: -80px; animation-duration: 25s; }
        .lp-hero-floating:nth-child(2) { width: 200px; height: 200px; bottom: -40px; left: -60px; animation-duration: 18s; animation-delay: 5s; }
        .lp-hero-floating:nth-child(3) { width: 150px; height: 150px; top: 40%; left: 15%; animation-duration: 22s; animation-delay: 3s; }
        @keyframes floatUp {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-30px) rotate(5deg); }
        }
        .lp-hero-content { position: relative; z-index: 2; }
        .lp-hero-badge {
            display: inline-flex; align-items: center; gap: .5rem;
            background: rgba(255,255,255,.1); border: 1px solid rgba(255,255,255,.15);
            padding: .4rem 1rem; border-radius: 50px; font-size: .8rem; color: rgba(255,255,255,.85);
            margin-bottom: 1.5rem; backdrop-filter: blur(10px);
        }
        .lp-hero-badge i { color: var(--lp-success); }
        .lp-hero h1 {
            font-size: 3.2rem; font-weight: 900; color: #fff; line-height: 1.15;
            margin-bottom: 1.5rem; letter-spacing: -.02em;
        }
        .lp-hero h1 .lp-gradient-text {
            background: linear-gradient(135deg, #60A5FA, #06B6D4);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .lp-hero-desc {
            font-size: 1.1rem; color: rgba(255,255,255,.7); line-height: 1.7;
            margin-bottom: 2rem; max-width: 540px;
        }
        .lp-hero-actions { display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 3rem; }
        .lp-btn-hero {
            padding: .85rem 2rem; border-radius: 10px; font-weight: 700; font-size: .95rem;
            text-decoration: none; transition: all .3s; display: inline-flex; align-items: center; gap: .5rem;
        }
        .lp-btn-hero-primary { background: #fff; color: var(--lp-secondary); }
        .lp-btn-hero-primary:hover { transform: translateY(-2px); box-shadow: 0 10px 40px rgba(255,255,255,.25); color: var(--lp-secondary); }
        .lp-btn-hero-outline { border: 2px solid rgba(255,255,255,.3); color: #fff; background: transparent; }
        .lp-btn-hero-outline:hover { background: rgba(255,255,255,.1); color: #fff; border-color: rgba(255,255,255,.6); }
        .lp-hero-stats {
            display: flex; gap: 2.5rem; padding-top: 2rem; border-top: 1px solid rgba(255,255,255,.1);
        }
        .lp-hero-stat-num { font-size: 1.8rem; font-weight: 800; color: #fff; }
        .lp-hero-stat-label { font-size: .8rem; color: rgba(255,255,255,.5); margin-top: .2rem; }
        .lp-hero-visual { position: relative; }
        .lp-hero-card-preview {
            background: rgba(255,255,255,.08); backdrop-filter: blur(20px);
            border: 1px solid rgba(255,255,255,.12); border-radius: 20px;
            padding: 2rem; color: #fff;
        }
        .lp-hero-card-preview .lp-mini-stat {
            display: flex; align-items: center; gap: .75rem; padding: .75rem 0;
            border-bottom: 1px solid rgba(255,255,255,.08);
        }
        .lp-hero-card-preview .lp-mini-stat:last-child { border-bottom: none; }
        .lp-mini-icon {
            width: 40px; height: 40px; border-radius: 10px; display: flex;
            align-items: center; justify-content: center; font-size: 1.1rem;
        }

        /* ═══ ABOUT SECTION ═══ */
        .lp-section { padding: 6rem 0; }
        .lp-section-title {
            font-size: 2.2rem; font-weight: 800; color: var(--lp-secondary);
            margin-bottom: .75rem; letter-spacing: -.01em;
        }
        .lp-section-subtitle { font-size: 1.05rem; color: var(--lp-muted); line-height: 1.7; max-width: 600px; }
        .lp-about-card {
            background: var(--lp-card); border: 1px solid var(--lp-border); border-radius: 16px;
            padding: 2rem; height: 100%; transition: all .3s;
        }
        .lp-about-card:hover { transform: translateY(-4px); box-shadow: 0 12px 40px rgba(0,0,0,.08); }
        .lp-about-icon {
            width: 52px; height: 52px; border-radius: 12px; display: flex;
            align-items: center; justify-content: center; font-size: 1.3rem; margin-bottom: 1rem;
        }

        /* ═══ FEATURES ═══ */
        .lp-features-bg { background: var(--lp-bg); }
        .lp-feature-card {
            background: var(--lp-card); border: 1px solid var(--lp-border); border-radius: 16px;
            padding: 2rem; height: 100%; transition: all .3s; position: relative; overflow: hidden;
        }
        .lp-feature-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--lp-primary), var(--lp-accent));
            transform: scaleX(0); transition: transform .3s; transform-origin: left;
        }
        .lp-feature-card:hover::before { transform: scaleX(1); }
        .lp-feature-card:hover { transform: translateY(-6px); box-shadow: 0 16px 48px rgba(0,0,0,.1); }
        .lp-feature-icon {
            width: 56px; height: 56px; border-radius: 14px; display: flex;
            align-items: center; justify-content: center; font-size: 1.4rem; margin-bottom: 1.2rem;
        }

        /* ═══ API SECTION ═══ */
        .lp-api-card {
            background: var(--lp-secondary); border-radius: 20px; padding: 3rem;
            color: #fff; position: relative; overflow: hidden;
        }
        .lp-api-card::before {
            content: ''; position: absolute; top: -50%; right: -20%; width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(37,99,235,.3), transparent 70%); border-radius: 50%;
        }
        .lp-api-badge {
            display: inline-flex; align-items: center; gap: .4rem;
            padding: .35rem .85rem; border-radius: 6px; font-size: .78rem; font-weight: 600;
        }
        .lp-api-endpoint {
            background: rgba(255,255,255,.06); border: 1px solid rgba(255,255,255,.1);
            border-radius: 10px; padding: 1rem 1.25rem; margin-bottom: .75rem;
            display: flex; align-items: center; justify-content: space-between;
        }
        .lp-api-method {
            font-size: .7rem; font-weight: 700; padding: .2rem .5rem; border-radius: 4px;
            letter-spacing: .5px;
        }

        /* ═══ FOOTER ═══ */
        .lp-footer {
            background: var(--lp-secondary); color: rgba(255,255,255,.6);
            padding: 4rem 0 2rem;
        }
        .lp-footer-brand { color: #fff; font-weight: 800; font-size: 1.2rem; margin-bottom: .75rem; }
        .lp-footer-brand span { color: var(--lp-primary-light); }
        .lp-footer h6 { color: rgba(255,255,255,.9); font-weight: 700; margin-bottom: 1rem; font-size: .85rem; letter-spacing: .5px; text-transform: uppercase; }
        .lp-footer-links { list-style: none; padding: 0; margin: 0; }
        .lp-footer-links li { margin-bottom: .5rem; }
        .lp-footer-links a { color: rgba(255,255,255,.5); text-decoration: none; font-size: .88rem; transition: color .2s; }
        .lp-footer-links a:hover { color: var(--lp-primary-light); }
        .lp-footer-bottom {
            border-top: 1px solid rgba(255,255,255,.08); padding-top: 2rem; margin-top: 3rem;
            display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;
        }

        /* ═══ RESPONSIVE ═══ */
        @media (max-width: 991px) {
            .lp-nav-links { display: none; }
            .lp-hero h1 { font-size: 2.4rem; }
            .lp-hero-stats { gap: 1.5rem; }
            .lp-hero-visual { margin-top: 3rem; }
        }
        @media (max-width: 575px) {
            .lp-hero h1 { font-size: 2rem; }
            .lp-hero-desc { font-size: 1rem; }
            .lp-hero-stats { flex-wrap: wrap; gap: 1rem; }
            .lp-section-title { font-size: 1.8rem; }
        }

        /* ═══ ANIMATIONS ═══ */
        .lp-fade-up {
            opacity: 0; transform: translateY(30px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .lp-fade-up.visible { opacity: 1; transform: translateY(0); }
    </style>
</head>
<body>

    <!-- ═══════════ NAVBAR ═══════════ -->
    <nav class="lp-navbar" id="lp-navbar">
        <div class="container d-flex align-items-center justify-content-between">
            <a href="{{ route('landing') }}" class="lp-nav-brand">
                <i class="bi bi-shield-shaded"></i> Supply<span>Chain</span>
            </a>
            <ul class="lp-nav-links d-none d-lg-flex">
                <li><a href="#about">Tentang</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="#api">API</a></li>
            </ul>
            <div class="d-flex align-items-center gap-2">
                <a href="{{ route('login') }}" class="lp-btn-outline">Masuk</a>
                <a href="{{ route('register') }}" class="lp-btn-primary-nav">Daftar Gratis</a>
            </div>
        </div>
    </nav>

    <!-- ═══════════ HERO ═══════════ -->
    <section class="lp-hero">
        <div class="lp-hero-floating"></div>
        <div class="lp-hero-floating"></div>
        <div class="lp-hero-floating"></div>

        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 lp-hero-content">
                    <div class="lp-hero-badge">
                        <i class="bi bi-lightning-charge-fill"></i>
                        Real-time Intelligence Platform
                    </div>
                    <h1>
                        Global Supply Chain<br>
                        <span class="lp-gradient-text">Risk Intelligence</span>
                    </h1>
                    <p class="lp-hero-desc">
                        Platform monitoring risiko rantai pasok global berbasis multi-API. 
                        Analisis cuaca, ekonomi, geopolitik, dan logistik dari 195+ negara secara real-time.
                    </p>
                    <div class="lp-hero-actions">
                        <a href="{{ route('login') }}" class="lp-btn-hero lp-btn-hero-primary">
                            <i class="bi bi-box-arrow-in-right"></i> Mulai Sekarang
                        </a>
                        <a href="#features" class="lp-btn-hero lp-btn-hero-outline">
                            <i class="bi bi-play-circle"></i> Pelajari Fitur
                        </a>
                    </div>
                    <div class="lp-hero-stats">
                        <div>
                            <div class="lp-hero-stat-num">195+</div>
                            <div class="lp-hero-stat-label">Negara Termonitor</div>
                        </div>
                        <div>
                            <div class="lp-hero-stat-num">6</div>
                            <div class="lp-hero-stat-label">API Terintegrasi</div>
                        </div>
                        <div>
                            <div class="lp-hero-stat-num">24/7</div>
                            <div class="lp-hero-stat-label">Real-time Data</div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1 lp-hero-visual d-none d-lg-block">
                    <div class="lp-hero-card-preview">
                        <h6 class="mb-3 fw-bold" style="font-size:.9rem;">
                            <i class="bi bi-activity text-info me-1"></i> Live Risk Monitor
                        </h6>
                        <div class="lp-mini-stat">
                            <div class="lp-mini-icon" style="background:rgba(34,197,94,.15);"><i class="bi bi-globe2" style="color:#22C55E;"></i></div>
                            <div><div class="fw-semibold" style="font-size:.88rem;">Indonesia</div><small style="color:rgba(255,255,255,.5);">Risk Score: 32 – Low</small></div>
                            <span class="badge ms-auto" style="background:rgba(34,197,94,.2);color:#22C55E;">Stabil</span>
                        </div>
                        <div class="lp-mini-stat">
                            <div class="lp-mini-icon" style="background:rgba(245,158,11,.15);"><i class="bi bi-globe2" style="color:#F59E0B;"></i></div>
                            <div><div class="fw-semibold" style="font-size:.88rem;">China</div><small style="color:rgba(255,255,255,.5);">Risk Score: 58 – Medium</small></div>
                            <span class="badge ms-auto" style="background:rgba(245,158,11,.2);color:#F59E0B;">Waspada</span>
                        </div>
                        <div class="lp-mini-stat">
                            <div class="lp-mini-icon" style="background:rgba(239,68,68,.15);"><i class="bi bi-globe2" style="color:#EF4444;"></i></div>
                            <div><div class="fw-semibold" style="font-size:.88rem;">Ukraine</div><small style="color:rgba(255,255,255,.5);">Risk Score: 89 – High</small></div>
                            <span class="badge ms-auto" style="background:rgba(239,68,68,.2);color:#EF4444;">Kritis</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ ABOUT ═══════════ -->
    <section class="lp-section" id="about">
        <div class="container">
            <div class="text-center mb-5 lp-fade-up">
                <h2 class="lp-section-title">Tentang Platform</h2>
                <p class="lp-section-subtitle mx-auto">
                    SupplyChain adalah platform intelijen yang menggabungkan data dari berbagai sumber API 
                    untuk memberikan gambaran risiko rantai pasok secara komprehensif.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-4 lp-fade-up">
                    <div class="lp-about-card">
                        <div class="lp-about-icon" style="background:rgba(37,99,235,.1);color:var(--lp-primary);">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-size:1.05rem;">Risk Assessment</h5>
                        <p class="text-muted mb-0" style="font-size:.9rem;line-height:1.6;">
                            Analisis risiko multi-dimensi mencakup faktor cuaca, ekonomi, geopolitik, dan logistik untuk setiap negara.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 lp-fade-up" style="transition-delay:.1s;">
                    <div class="lp-about-card">
                        <div class="lp-about-icon" style="background:rgba(6,182,212,.1);color:var(--lp-accent);">
                            <i class="bi bi-graph-up-arrow"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-size:1.05rem;">Real-time Analytics</h5>
                        <p class="text-muted mb-0" style="font-size:.9rem;line-height:1.6;">
                            Data diperbarui secara real-time dari 6+ API provider global untuk memastikan keakuratan informasi.
                        </p>
                    </div>
                </div>
                <div class="col-md-4 lp-fade-up" style="transition-delay:.2s;">
                    <div class="lp-about-card">
                        <div class="lp-about-icon" style="background:rgba(34,197,94,.1);color:var(--lp-success);">
                            <i class="bi bi-globe2"></i>
                        </div>
                        <h5 class="fw-bold mb-2" style="font-size:1.05rem;">Global Coverage</h5>
                        <p class="text-muted mb-0" style="font-size:.9rem;line-height:1.6;">
                            Monitoring 195+ negara dengan data pelabuhan, cuaca, nilai tukar, berita, dan indikator risiko terintegrasi.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ FEATURES ═══════════ -->
    <section class="lp-section lp-features-bg" id="features">
        <div class="container">
            <div class="text-center mb-5 lp-fade-up">
                <h2 class="lp-section-title">Fitur Utama</h2>
                <p class="lp-section-subtitle mx-auto">
                    Semua tools yang Anda butuhkan untuk memantau dan menganalisis risiko rantai pasok global dalam satu platform.
                </p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-4 lp-fade-up">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background:rgba(37,99,235,.1);color:var(--lp-primary);"><i class="bi bi-globe2"></i></div>
                        <h5 class="fw-bold mb-2" style="font-size:1rem;">Country Intelligence</h5>
                        <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.6;">Profil lengkap 195+ negara dengan data demografi, ekonomi, dan indikator risiko terkini.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 lp-fade-up" style="transition-delay:.05s;">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background:rgba(6,182,212,.1);color:var(--lp-accent);"><i class="bi bi-cloud-sun-fill"></i></div>
                        <h5 class="fw-bold mb-2" style="font-size:1rem;">Weather Monitoring</h5>
                        <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.6;">Pantau kondisi cuaca real-time dan prediksi dampaknya terhadap jalur distribusi logistik.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 lp-fade-up" style="transition-delay:.1s;">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background:rgba(34,197,94,.1);color:var(--lp-success);"><i class="bi bi-cash-stack"></i></div>
                        <h5 class="fw-bold mb-2" style="font-size:1rem;">Currency Exchange</h5>
                        <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.6;">Tracking nilai tukar mata uang real-time untuk analisis dampak ekonomi pada rantai pasok.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 lp-fade-up" style="transition-delay:.15s;">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background:rgba(245,158,11,.1);color:var(--lp-warning);"><i class="bi bi-newspaper"></i></div>
                        <h5 class="fw-bold mb-2" style="font-size:1rem;">News Analysis</h5>
                        <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.6;">Agregasi berita global terkait supply chain, geopolitik, dan faktor risiko dari sumber terpercaya.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 lp-fade-up" style="transition-delay:.2s;">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background:rgba(239,68,68,.1);color:var(--lp-danger);"><i class="bi bi-exclamation-triangle-fill"></i></div>
                        <h5 class="fw-bold mb-2" style="font-size:1rem;">Risk Analysis</h5>
                        <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.6;">Skor risiko kalkulatif dari multiple data points untuk setiap negara dengan visualisasi intuitif.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 lp-fade-up" style="transition-delay:.25s;">
                    <div class="lp-feature-card">
                        <div class="lp-feature-icon" style="background:rgba(139,92,246,.1);color:#8B5CF6;"><i class="bi bi-bar-chart-steps"></i></div>
                        <h5 class="fw-bold mb-2" style="font-size:1rem;">Data Visualization</h5>
                        <p class="text-muted mb-0" style="font-size:.88rem;line-height:1.6;">Grafik interaktif dan dashboard visual untuk perbandingan lintas negara dan tren historis.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ API INFO ═══════════ -->
    <section class="lp-section" id="api">
        <div class="container">
            <div class="lp-api-card lp-fade-up">
                <div class="row align-items-center" style="position:relative;z-index:2;">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <div class="lp-hero-badge" style="border-color:rgba(255,255,255,.1);">
                            <i class="bi bi-code-slash" style="color:var(--lp-accent);"></i> API Integration
                        </div>
                        <h2 class="fw-bold mb-3" style="font-size:1.8rem;">Multi-API<br>Data Pipeline</h2>
                        <p style="color:rgba(255,255,255,.6);font-size:.92rem;line-height:1.7;">
                            Platform terintegrasi dengan 6 API provider global untuk menyediakan data real-time yang komprehensif.
                        </p>
                    </div>
                    <div class="col-lg-6 offset-lg-1">
                        <div class="lp-api-endpoint">
                            <div class="d-flex align-items-center gap-2">
                                <span class="lp-api-method" style="background:rgba(34,197,94,.2);color:#22C55E;">GET</span>
                                <code style="color:rgba(255,255,255,.8);font-size:.85rem;">/api/countries</code>
                            </div>
                            <span class="lp-api-badge" style="background:rgba(34,197,94,.15);color:#22C55E;">REST Countries</span>
                        </div>
                        <div class="lp-api-endpoint">
                            <div class="d-flex align-items-center gap-2">
                                <span class="lp-api-method" style="background:rgba(6,182,212,.2);color:#06B6D4;">GET</span>
                                <code style="color:rgba(255,255,255,.8);font-size:.85rem;">/api/weather</code>
                            </div>
                            <span class="lp-api-badge" style="background:rgba(6,182,212,.15);color:#06B6D4;">OpenWeather</span>
                        </div>
                        <div class="lp-api-endpoint">
                            <div class="d-flex align-items-center gap-2">
                                <span class="lp-api-method" style="background:rgba(245,158,11,.2);color:#F59E0B;">GET</span>
                                <code style="color:rgba(255,255,255,.8);font-size:.85rem;">/api/currency</code>
                            </div>
                            <span class="lp-api-badge" style="background:rgba(245,158,11,.15);color:#F59E0B;">ExchangeRate</span>
                        </div>
                        <div class="lp-api-endpoint">
                            <div class="d-flex align-items-center gap-2">
                                <span class="lp-api-method" style="background:rgba(239,68,68,.2);color:#EF4444;">GET</span>
                                <code style="color:rgba(255,255,255,.8);font-size:.85rem;">/api/news</code>
                            </div>
                            <span class="lp-api-badge" style="background:rgba(239,68,68,.15);color:#EF4444;">NewsAPI</span>
                        </div>
                        <div class="lp-api-endpoint" style="margin-bottom:0;">
                            <div class="d-flex align-items-center gap-2">
                                <span class="lp-api-method" style="background:rgba(139,92,246,.2);color:#8B5CF6;">GET</span>
                                <code style="color:rgba(255,255,255,.8);font-size:.85rem;">/api/risk</code>
                            </div>
                            <span class="lp-api-badge" style="background:rgba(139,92,246,.15);color:#8B5CF6;">Composite</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══════════ FOOTER ═══════════ -->
    <footer class="lp-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="lp-footer-brand"><i class="bi bi-shield-shaded me-1"></i> Supply<span>Chain</span></div>
                    <p style="font-size:.88rem;line-height:1.7;max-width:300px;">
                        Platform monitoring risiko rantai pasok global berbasis multi-API dan analitik data real-time.
                    </p>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Platform</h6>
                    <ul class="lp-footer-links">
                        <li><a href="#features">Fitur</a></li>
                        <li><a href="#api">API</a></li>
                        <li><a href="#about">Tentang</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-2">
                    <h6>Akses</h6>
                    <ul class="lp-footer-links">
                        <li><a href="{{ route('login') }}">Login</a></li>
                        <li><a href="{{ route('register') }}">Register</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h6>Kontak</h6>
                    <ul class="lp-footer-links">
                        <li><a href="#"><i class="bi bi-envelope me-2"></i>support@supplychain.id</a></li>
                        <li><a href="#"><i class="bi bi-geo-alt me-2"></i>Jakarta, Indonesia</a></li>
                    </ul>
                </div>
            </div>
            <div class="lp-footer-bottom">
                <span style="font-size:.82rem;">© {{ date('Y') }} SupplyChain Platform. All rights reserved.</span>
                <span style="font-size:.82rem;">Built with Laravel {{ app()->version() }}</span>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            document.getElementById('lp-navbar').classList.toggle('scrolled', window.scrollY > 50);
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(a => {
            a.addEventListener('click', e => {
                e.preventDefault();
                const target = document.querySelector(a.getAttribute('href'));
                if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });

        // Scroll reveal animation
        const observer = new IntersectionObserver(entries => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.lp-fade-up').forEach(el => observer.observe(el));
    </script>
</body>
</html>
