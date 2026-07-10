@extends('layouts.app')

@section('title', 'Dashboard Control Center')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom border-secondary border-opacity-25">
    <h1 class="h2 text-white">Dashboard Control Center</h1>
    <div class="btn-toolbar mb-2 mb-md-0">
        <div class="btn-group me-2">
            <button type="button" class="btn btn-sm btn-outline-secondary glass-card text-white"><i class="bi bi-share me-1"></i> Bagikan</button>
            <button type="button" class="btn btn-sm btn-outline-secondary glass-card text-white"><i class="bi bi-download me-1"></i> Ekspor</button>
        </div>
        <button type="button" class="btn btn-sm btn-primary d-flex align-items-center">
            <i class="bi bi-calendar3 me-2"></i> Hari Ini
        </button>
    </div>
</div>

<!-- Welcome Message -->
<div class="row mb-4">
    <div class="col-12">
        <div class="glass-card p-4 d-flex align-items-center justify-content-between position-relative overflow-hidden">
            <div class="position-relative" style="z-index: 2;">
                <h3 class="text-white">Selamat Datang di Control Center, {{ Auth::user()->name }}!</h3>
                <p class="text-secondary mb-0">Platform saat ini aktif mengawasi rantai pasokan global Anda. Semua sensor API berjalan normal.</p>
            </div>
            <div class="d-none d-lg-block text-glow-cyan" style="font-size: 5rem; z-index: 1; opacity: 0.15; transform: rotate(-10deg);">
                <i class="bi bi-shield-check"></i>
            </div>
        </div>
    </div>
</div>

<!-- KPI Indicators -->
<div class="row g-4 mb-4">
    <!-- Active Countries -->
    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-secondary small fw-medium">Negara Terpantau</span>
                <h3 class="display-6 fw-bold text-glow-cyan mt-1">4</h3>
            </div>
            <div class="d-flex align-items-center text-success small mt-3">
                <i class="bi bi-check-circle-fill me-1"></i> Semua API Terkoneksi
            </div>
        </div>
    </div>
    <!-- Cargo Ports -->
    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-secondary small fw-medium">Pelabuhan Kargo</span>
                <h3 class="display-6 fw-bold text-glow-purple mt-1">8</h3>
            </div>
            <div class="d-flex align-items-center text-success small mt-3">
                <i class="bi bi-check-circle-fill me-1"></i> Lokasi Terpetakan
            </div>
        </div>
    </div>
    <!-- Active Warnings -->
    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-secondary small fw-medium">Alert Risiko Aktif</span>
                <h3 class="display-6 fw-bold text-success mt-1">0</h3>
            </div>
            <div class="d-flex align-items-center text-success small mt-3">
                <i class="bi bi-check-circle-fill me-1"></i> Rute Logistik Aman
            </div>
        </div>
    </div>
    <!-- Global Risk Index -->
    <div class="col-md-3">
        <div class="glass-card p-4 h-100 d-flex flex-column justify-content-between">
            <div>
                <span class="text-secondary small fw-medium">Global Risk Index</span>
                <h3 class="display-6 fw-bold text-glow-cyan mt-1">Low</h3>
            </div>
            <div class="d-flex align-items-center text-secondary small mt-3">
                Rata-rata Skor: 28% (Aman)
            </div>
        </div>
    </div>
</div>

<!-- Map and Chart Grid Placeholders -->
<div class="row g-4">
    <!-- Map Placeholder -->
    <div class="col-lg-7">
        <div class="glass-card p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="text-white mb-0"><i class="bi bi-globe me-2 text-glow-cyan"></i> Peta Risiko Logistik Global</h5>
                <span class="badge bg-secondary glass-card text-white py-1.5 px-3">Live Map</span>
            </div>
            <div id="main-map" style="height: 380px; border-radius: 12px; border: 1px solid var(--card-border);"></div>
        </div>
    </div>
    
    <!-- Latest News Feed Placeholder -->
    <div class="col-lg-5">
        <div class="glass-card p-4">
            <h5 class="text-white mb-3"><i class="bi bi-newspaper me-2 text-glow-purple"></i> Intelijen Berita Geopolitik</h5>
            <div class="list-group list-group-flush" style="max-height: 380px; overflow-y: auto;">
                <div class="list-group-item bg-transparent text-white border-secondary border-opacity-25 px-0 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge badge-low">Positive</span>
                        <small class="text-secondary">2 Jam Yang Lalu</small>
                    </div>
                    <h6 class="mb-1 text-glow-cyan">Ekspansi Dagang Jerman-Australia Memperkuat Rantai Pasok Logistik</h6>
                    <p class="text-secondary small mb-0">Pertemuan bilateral membahas kerja sama pengapalan bahan baku mineral...</p>
                </div>
                <div class="list-group-item bg-transparent text-white border-secondary border-opacity-25 px-0 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge bg-secondary glass-card text-secondary py-0.5 px-2" style="font-size: 0.75rem;">Neutral</span>
                        <small class="text-secondary">5 Jam Yang Lalu</small>
                    </div>
                    <h6 class="mb-1">Tanjung Priok Memulai Program Digitalisasi Sistem Kontainer Logistik</h6>
                    <p class="text-secondary small mb-0">Otoritas pelabuhan meluncurkan integrasi API guna mengurangi antrean kapal...</p>
                </div>
                <div class="list-group-item bg-transparent text-white border-secondary border-opacity-25 px-0 py-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="badge badge-high">Negative</span>
                        <small class="text-secondary">1 Hari Yang Lalu</small>
                    </div>
                    <h6 class="mb-1 text-danger">Kenaikan Inflasi Global Berpotensi Mengganggu Jalur Pengapalan Komoditas</h6>
                    <p class="text-secondary small mb-0">Analisis ekonomi memprediksi adanya kenaikan biaya operasional kargo laut...</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Leaflet map centered globally
        const map = L.map('main-map').setView([10.0, 115.0], 3);

        // Load custom dark map tiles from CartoDB (perfect for dark dashboards)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; CartoDB &copy; OpenStreetMap contributors'
        }).addTo(map);

        // Fetch countries and ports to render markers (We will draw markers on the map)
        const sampleLocations = [
            { name: "Port of Hamburg (Jerman)", coords: [53.54, 9.92], status: "Aman" },
            { name: "Port of Shanghai (China)", coords: [30.62, 122.06], status: "Aman" },
            { name: "Port of Tanjung Priok (Indonesia)", coords: [-6.10, 106.88], status: "Waspada" },
            { name: "Port of Sydney (Australia)", coords: [-33.86, 151.21], status: "Aman" }
        ];

        sampleLocations.forEach(loc => {
            const markerColor = loc.status === "Aman" ? "#10B981" : "#F59E0B";
            
            // Create a custom pulsing marker style using SVG/DivIcon
            const icon = L.divIcon({
                className: 'custom-div-icon',
                html: `<div style="background-color: ${markerColor}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 0 8px ${markerColor};"></div>`,
                iconSize: [12, 12]
            });

            L.marker(loc.coords, { icon: icon })
                .addTo(map)
                .bindPopup(`<strong class="text-dark">${loc.name}</strong><br><span class="text-muted">Status: ${loc.status}</span>`);
        });
    });
</script>
@endsection
