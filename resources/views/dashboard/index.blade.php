@extends('layouts.user.app')

@section('title', 'Dashboard Global Supply Chain - SupplyChain Platform')

@section('content')
<div class="container-fluid p-0 fade-in-up">

    <!-- Header Dashboard -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1">Dashboard Global Supply Chain</h3>
                        <p class="text-secondary small mb-0">Pantau kondisi rantai pasok dunia secara real-time.</p>
                    </div>
                    <!-- Live connection status badge -->
                    <div class="d-flex align-items-center gap-3">
                        <span class="text-secondary small d-none d-lg-inline-block">
                            <span class="pulse-indicator"></span>Layanan Intelijen Aktif
                        </span>
                        <button class="btn btn-primary btn-refresh-all" onclick="refreshDashboardData()">
                            <i class="bi bi-arrow-clockwise me-2"></i>Segarkan Dasbor
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="row g-4 mb-4">
        <!-- KPI 1: Negara Dipantau -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Negara Dipantau</span>
                    <h3 class="fw-bold text-dark mb-1">195</h3>
                    <span class="text-success small fw-semibold"><i class="bi bi-arrow-up-right me-1"></i>100% Cakupan</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(37, 99, 235, 0.08); color: var(--primary);">
                    <i class="bi bi-globe2 fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 2: Pelabuhan Aktif -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Pelabuhan Aktif</span>
                    <h3 class="fw-bold text-dark mb-1">412</h3>
                    <span class="text-success small fw-semibold"><i class="bi bi-plus-circle me-1"></i>+2.4% Bulan Ini</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(6, 182, 212, 0.08); color: var(--info);">
                    <i class="bi bi-anchor fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 3: Berita Hari Ini -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Berita Hari Ini</span>
                    <h3 class="fw-bold text-dark mb-1">24</h3>
                    <span class="text-primary small fw-semibold"><i class="bi bi-lightning-fill me-1"></i>8 Berita Kritis</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                    <i class="bi bi-newspaper fs-3"></i>
                </div>
            </div>
        </div>

        <!-- KPI 4: Skor Risiko Global -->
        <div class="col-xl-3 col-md-6">
            <div class="card p-4 h-100 border-0 d-flex flex-row align-items-center justify-content-between">
                <div>
                    <span class="text-secondary small fw-medium d-block mb-1">Skor Risiko Global</span>
                    <h3 class="fw-bold text-success mb-1">2.8</h3>
                    <span class="text-success small fw-semibold"><i class="bi bi-arrow-down-left me-1"></i>-1.2% Menurun</span>
                </div>
                <div class="p-3 rounded-4" style="background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                    <i class="bi bi-shield-exclamation fs-3"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Section: Map & Risk Overview -->
    <div class="row g-4 mb-4">
        <!-- World Map Placeholder -->
        <div class="col-lg-8">
            <div class="card p-4 h-100 border-0">
                <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="bi bi-map-fill text-primary me-2"></i>Peta Koridor Risiko & Rantai Pasok Global</h5>
                        <p class="text-secondary small mb-0">Klik pada titik hub pelabuhan untuk melihat analisis detail.</p>
                    </div>
                    <!-- Map Filters -->
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm py-1.5" id="map-region-filter" style="width: auto; min-height: 38px;" onchange="filterMapNodes()">
                            <option value="all">Semua Benua</option>
                            <option value="asia">Asia</option>
                            <option value="europe">Eropa</option>
                            <option value="america">Amerika</option>
                        </select>
                        <select class="form-select form-select-sm py-1.5" id="map-risk-filter" style="width: auto; min-height: 38px;" onchange="filterMapNodes()">
                            <option value="all">Semua Risiko</option>
                            <option value="high">Risiko Tinggi</option>
                            <option value="medium">Risiko Sedang</option>
                            <option value="low">Risiko Rendah</option>
                        </select>
                    </div>
                </div>

                <!-- Interactive SVG Map Area -->
                <div class="position-relative border rounded-4 overflow-hidden bg-light d-flex align-items-center justify-content-center" style="height: 450px; background-color: #FAFCFF !important; background-image: radial-gradient(#E2E8F0 1.2px, transparent 1.2px); background-size: 24px 24px;">
                    
                    <!-- Zoom Controls -->
                    <div class="position-absolute top-0 start-0 m-3 d-flex flex-column gap-1.5 style-zoom-controls" style="z-index: 10;">
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomMap(1.2)" style="width: 36px; height: 36px; min-height: 36px;" title="Perbesar">
                            <i class="bi bi-zoom-in"></i>
                        </button>
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="zoomMap(0.8)" style="width: 36px; height: 36px; min-height: 36px;" title="Perkecil">
                            <i class="bi bi-zoom-out"></i>
                        </button>
                        <button class="btn btn-light btn-sm border p-2 d-flex align-items-center justify-content-center" onclick="resetMapZoom()" style="width: 36px; height: 36px; min-height: 36px;" title="Reset Zoom">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>

                    <!-- Legend -->
                    <div class="position-absolute bottom-0 start-0 m-3 bg-white p-2.5 rounded-3 border d-flex flex-column gap-1" style="z-index: 10; font-size: 0.75rem; box-shadow: 0 4px 12px rgba(0,0,0,0.02);">
                        <span class="fw-bold text-dark mb-1">Legenda Risiko</span>
                        <div class="d-flex align-items-center"><span class="badge badge-danger me-1.5" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"></span> Tinggi (>4.0)</div>
                        <div class="d-flex align-items-center"><span class="badge badge-warning me-1.5" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"></span> Sedang (2.5 - 4.0)</div>
                        <div class="d-flex align-items-center"><span class="badge badge-success me-1.5" style="width: 8px; height: 8px; padding: 0; border-radius: 50%;"></span> Rendah (<2.5)</div>
                    </div>

                    <!-- World Map SVG wrapper -->
                    <div id="map-container" class="w-100 h-100 d-flex align-items-center justify-content-center" style="transition: transform 0.3s ease; transform-origin: center;">
                        <svg viewBox="0 0 1000 500" class="w-100 h-100" style="max-height: 420px;">
                            <!-- Stylized Continents Outlines (Paths) -->
                            <g fill="#E2E8F0" stroke="#FFFFFF" stroke-width="1.5">
                                <!-- North America -->
                                <path d="M100,80 L200,60 L280,100 L250,180 L200,200 L150,150 Z" />
                                <!-- South America -->
                                <path d="M250,220 L320,250 L280,380 L240,420 L220,300 Z" />
                                <!-- Greenland -->
                                <path d="M300,30 L380,20 L350,70 L280,60 Z" />
                                <!-- Eurasia (Europe & Asia) -->
                                <path d="M450,60 L600,40 L850,50 L900,120 L800,280 L700,250 L600,280 L520,220 L420,120 Z" />
                                <!-- Africa -->
                                <path d="M460,180 L560,160 L630,220 L580,350 L520,380 L480,260 Z" />
                                <!-- Australia -->
                                <path d="M780,320 L880,300 L850,380 L760,360 Z" />
                            </g>

                            <!-- Shipping Lanes (Dotted Lines connecting hubs) -->
                            <g stroke="rgba(37, 99, 235, 0.25)" stroke-width="1.5" stroke-dasharray="4 4" fill="none">
                                <path d="M180,130 C 250,150 480,120 520,130" /> <!-- USA East to Europe -->
                                <path d="M520,130 C 580,180 500,240 500,240" /> <!-- Europe to Africa -->
                                <path d="M500,240 C 580,320 680,280 650,230" /> <!-- Africa to SG -->
                                <path d="M520,130 C 580,150 630,200 650,230" /> <!-- Europe to SG via Suez -->
                                <path d="M650,230 C 700,220 780,200 780,120" /> <!-- SG to Shanghai -->
                                <path d="M780,120 C 680,140 600,180 570,220" /> <!-- Shanghai to Tanjung Priok -->
                                <path d="M570,220 C 600,225 630,228 650,230" /> <!-- Tanjung Priok to SG -->
                                <path d="M780,120 C 850,110 900,115 950,120" /> <!-- Shanghai to US West -->
                                <path d="M180,130 C 240,240 250,220 270,260" /> <!-- USA East to Brazil -->
                            </g>

                            <!-- Interactive Hub Nodes (Ports) -->
                            <!-- format: circle: cx, cy, class (map-node), data-attrs (country, port, risk, risk-level, weather, continent) -->
                            <g id="map-nodes">
                                <!-- Tanjung Priok (Indonesia) -->
                                <circle cx="570" cy="220" r="9" class="map-node node-success" data-country="Indonesia" data-port="Tanjung Priok, Jakarta" data-risk="1.25" data-level="low" data-weather="Hujan Ringan (28°C)" data-continent="asia" onclick="showNodeDetails(this)" onmouseover="showMapTooltip(event, this)" onmouseout="hideMapTooltip()" />
                                
                                <!-- Port of Singapore (Singapore) -->
                                <circle cx="650" cy="230" r="9" class="map-node node-success" data-country="Singapura" data-port="Port of Singapore" data-risk="0.95" data-level="low" data-weather="Cerah (31°C)" data-continent="asia" onclick="showNodeDetails(this)" onmouseover="showMapTooltip(event, this)" onmouseout="hideMapTooltip()" />
                                
                                <!-- Port of Shanghai (China) -->
                                <circle cx="780" cy="120" r="11" class="map-node node-danger animate-pulse-node" data-country="China" data-port="Port of Shanghai" data-risk="4.92" data-level="high" data-weather="Badai Tropis (24°C)" data-continent="asia" onclick="showNodeDetails(this)" onmouseover="showMapTooltip(event, this)" onmouseout="hideMapTooltip()" />
                                
                                <!-- Port of Rotterdam (Rotterdam/Netherlands) -->
                                <circle cx="520" cy="130" r="9" class="map-node node-warning" data-country="Belanda" data-port="Port of Rotterdam" data-risk="2.85" data-level="medium" data-weather="Berawan (17°C)" data-continent="europe" onclick="showNodeDetails(this)" onmouseover="showMapTooltip(event, this)" onmouseout="hideMapTooltip()" />
                                
                                <!-- Port of Los Angeles (USA West) -->
                                <circle cx="150" cy="110" r="9" class="map-node node-warning" data-country="Amerika Serikat (Barat)" data-port="Port of Los Angeles" data-risk="3.48" data-level="medium" data-weather="Mendung (21°C)" data-continent="america" onclick="showNodeDetails(this)" onmouseover="showMapTooltip(event, this)" onmouseout="hideMapTooltip()" />
                                
                                <!-- Port of New York (USA East) -->
                                <circle cx="180" cy="130" r="9" class="map-node node-success" data-country="Amerika Serikat (Timur)" data-port="Port of New York" data-risk="2.10" data-level="low" data-weather="Cerah (25°C)" data-continent="america" onclick="showNodeDetails(this)" onmouseover="showMapTooltip(event, this)" onmouseout="hideMapTooltip()" />

                                <!-- Port of Santos (Brazil) -->
                                <circle cx="270" cy="260" r="9" class="map-node node-success" data-country="Brasil" data-port="Port of Santos" data-risk="2.35" data-level="low" data-weather="Hujan Badai (26°C)" data-continent="america" onclick="showNodeDetails(this)" onmouseover="showMapTooltip(event, this)" onmouseout="hideMapTooltip()" />

                                <!-- Port of Durban (South Africa) -->
                                <circle cx="500" cy="240" r="9" class="map-node node-warning" data-country="Afrika Selatan" data-port="Port of Durban" data-risk="3.15" data-level="medium" data-weather="Cerah (19°C)" data-continent="africa" onclick="showNodeDetails(this)" onmouseover="showMapTooltip(event, this)" onmouseout="hideMapTooltip()" />
                            </g>
                        </svg>
                    </div>

                    <!-- Dynamic Floating Tooltip -->
                    <div id="map-tooltip" class="position-absolute bg-white px-3 py-2.5 rounded-3 border shadow-sm text-start" style="display: none; pointer-events: none; z-index: 100; font-size: 0.8rem; min-width: 180px;">
                        <!-- Filled by JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Risk Overview Panel (Right Side) -->
        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-shield-slash text-danger me-2"></i>Ikhtisar Risiko Global</h5>
                <p class="text-secondary small mb-3">Peta pemantauan indeks kerentanan koridor rantai pasok.</p>
                
                <div class="d-flex flex-column gap-3">
                    <!-- Top 5 High Risk -->
                    <div>
                        <h6 class="fw-bold text-danger mb-2.5" style="font-size: 0.85rem;"><i class="bi bi-exclamation-triangle-fill me-1"></i>Top 5 Risiko Tinggi</h6>
                        <div class="d-flex flex-column gap-2">
                            <!-- Sudan -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇸🇩 Sudan</span>
                                    <span class="badge badge-danger">8.80 / Kritis</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-danger" style="width: 88%;"></div>
                                </div>
                            </div>
                            <!-- Yaman -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇾🇪 Yaman</span>
                                    <span class="badge badge-danger">8.50 / Kritis</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-danger" style="width: 85%;"></div>
                                </div>
                            </div>
                            <!-- Suriah -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇸🇾 Suriah</span>
                                    <span class="badge badge-danger">8.20 / Kritis</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-danger" style="width: 82%;"></div>
                                </div>
                            </div>
                            <!-- Ukraina -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇺🇦 Ukraina</span>
                                    <span class="badge badge-danger">7.90 / Tinggi</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-danger" style="width: 79%;"></div>
                                </div>
                            </div>
                            <!-- Somalia -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇸🇴 Somalia</span>
                                    <span class="badge badge-danger">7.60 / Tinggi</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-danger" style="width: 76%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-top pt-2.5">
                        <h6 class="fw-bold text-success mb-2.5" style="font-size: 0.85rem;"><i class="bi bi-shield-check me-1"></i>Top 5 Risiko Rendah (Stabil)</h6>
                        <div class="d-flex flex-column gap-2">
                            <!-- Singapura -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇸🇬 Singapura</span>
                                    <span class="badge badge-success">0.95 / Aman</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-success" style="width: 10%;"></div>
                                </div>
                            </div>
                            <!-- Swiss -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇨🇭 Swiss</span>
                                    <span class="badge badge-success">1.10 / Aman</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-success" style="width: 11%;"></div>
                                </div>
                            </div>
                            <!-- Denmark -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇩🇰 Denmark</span>
                                    <span class="badge badge-success">1.20 / Aman</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-success" style="width: 12%;"></div>
                                </div>
                            </div>
                            <!-- Indonesia -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇮🇩 Indonesia</span>
                                    <span class="badge badge-success">1.25 / Aman</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-success" style="width: 12.5%;"></div>
                                </div>
                            </div>
                            <!-- Jepang -->
                            <div>
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <span class="text-dark small fw-medium">🇯🇵 Jepang</span>
                                    <span class="badge badge-success">1.30 / Aman</span>
                                </div>
                                <div class="progress" style="height: 6px; background-color: #E2E8F0;">
                                    <div class="progress-bar bg-success" style="width: 13%;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row: Trend Chart & Weather & Exchange Rates -->
    <div class="row g-4 mb-4">
        <!-- Global Risk Area Chart -->
        <div class="col-lg-8">
            <div class="card p-4 h-100 border-0">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold text-dark mb-0"><i class="bi bi-graph-up-arrow text-primary me-2"></i>Tren Indeks Kerentanan Rantai Pasok Global (30 Hari)</h5>
                    <span class="badge badge-primary rounded-pill px-2.5 py-1" style="font-size: 0.7rem;">Kontrol Intelijen</span>
                </div>

                <!-- Custom SVG Graph representing Area Chart -->
                <div class="border rounded-4 position-relative d-flex align-items-center justify-content-center overflow-hidden" style="height: 310px; background-color: #FAFCFF !important;" onmousemove="trackChartCursor(event)" onmouseleave="hideChartTracker()">
                    <!-- SVG Area Chart -->
                    <svg viewBox="0 0 700 240" class="w-100 h-100 px-3 py-2">
                        <!-- Gradients definitions -->
                        <defs>
                            <linearGradient id="chartGradient" x1="0" y1="0" x2="0" y2="1">
                                <stop offset="0%" stop-color="var(--primary)" stop-opacity="0.3"></stop>
                                <stop offset="100%" stop-color="var(--primary)" stop-opacity="0.0"></stop>
                            </linearGradient>
                        </defs>
                        <!-- Grid Lines -->
                        <line x1="50" y1="40" x2="650" y2="40" stroke="#E2E8F0" stroke-dasharray="4"></line>
                        <line x1="50" y1="90" x2="650" y2="90" stroke="#E2E8F0" stroke-dasharray="4"></line>
                        <line x1="50" y1="140" x2="650" y2="140" stroke="#E2E8F0" stroke-dasharray="4"></line>
                        <line x1="50" y1="190" x2="650" y2="190" stroke="#E2E8F0" stroke-dasharray="4"></line>

                        <!-- Chart Area Path -->
                        <path d="M50,190 L100,170 Q150,130 200,140 T300,120 T400,110 T500,140 Q550,160 600,130 L650,90 L650,190 Z" fill="url(#chartGradient)"></path>
                        <!-- Chart Line Path -->
                        <path d="M50,190 L100,170 Q150,130 200,140 T300,120 T400,110 T500,140 Q550,160 600,130 L650,90" fill="none" stroke="var(--primary)" stroke-width="3" stroke-linecap="round"></path>

                        <!-- Dots on chart -->
                        <circle cx="50" cy="190" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="100" cy="170" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="200" cy="140" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="300" cy="120" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="400" cy="110" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="500" cy="140" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="600" cy="130" r="4" fill="var(--primary)" stroke="#FFFFFF" stroke-width="1.5"></circle>
                        <circle cx="650" cy="90" r="5" fill="var(--danger)" stroke="#FFFFFF" stroke-width="2"></circle>

                        <!-- Axis Labels -->
                        <text x="50" y="210" fill="#94A3B8" font-size="10" text-anchor="middle">H-30</text>
                        <text x="200" y="210" fill="#94A3B8" font-size="10" text-anchor="middle">H-20</text>
                        <text x="400" y="210" fill="#94A3B8" font-size="10" text-anchor="middle">H-10</text>
                        <text x="650" y="210" fill="#94A3B8" font-size="10" text-anchor="middle">Hari Ini</text>

                        <text x="35" y="44" fill="#94A3B8" font-size="10" text-anchor="end">5.0</text>
                        <text x="35" y="94" fill="#94A3B8" font-size="10" text-anchor="end">3.7</text>
                        <text x="35" y="144" fill="#94A3B8" font-size="10" text-anchor="end">2.5</text>
                        <text x="35" y="194" fill="#94A3B8" font-size="10" text-anchor="end">1.2</text>
                    </svg>

                    <!-- Interactive Guide Line and Tooltip on Chart Hover -->
                    <div id="chart-guide-line" class="position-absolute bg-secondary" style="width: 1px; top: 0; bottom: 0; left: 0; display: none; pointer-events: none; opacity: 0.4;"></div>
                    <div id="chart-tooltip" class="position-absolute bg-white px-2 py-1.5 rounded-2 border shadow-sm text-start" style="display: none; pointer-events: none; z-index: 10; font-size: 0.75rem;">
                        <!-- Content updated via JS -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Weather Port Widgets -->
        <div class="col-lg-4">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-cloud-sun-fill text-primary me-2"></i>Kondisi Cuaca Pelabuhan Utama</h5>
                <p class="text-secondary small mb-3">Laporan iklim pelabuhan utama rantai pasok global.</p>

                <div class="d-flex flex-column gap-2.5">
                    <!-- Tanjung Priok -->
                    <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cloud-rain-heavy-fill text-primary fs-3 me-3"></i>
                            <div>
                                <span class="text-dark fw-bold small d-block">Tanjung Priok</span>
                                <span class="text-secondary" style="font-size: 0.725rem;">Jakarta, ID | Kelembaban: 85%</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="text-dark fw-bold d-block">28°C</span>
                            <span class="text-secondary small" style="font-size: 0.7rem;"><i class="bi bi-wind me-1"></i>12 km/j</span>
                        </div>
                    </div>

                    <!-- Port of Singapore -->
                    <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-sun-fill text-warning fs-3 me-3"></i>
                            <div>
                                <span class="text-dark fw-bold small d-block">Port of Singapore</span>
                                <span class="text-secondary" style="font-size: 0.725rem;">Singapura, SG | Kelembaban: 60%</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="text-dark fw-bold d-block">31°C</span>
                            <span class="text-secondary small" style="font-size: 0.7rem;"><i class="bi bi-wind me-1"></i>8 km/j</span>
                        </div>
                    </div>

                    <!-- Port of Rotterdam -->
                    <div class="p-3 border rounded-4 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-cloud-sun-fill text-primary fs-3 me-3"></i>
                            <div>
                                <span class="text-dark fw-bold small d-block">Port of Rotterdam</span>
                                <span class="text-secondary" style="font-size: 0.725rem;">Rotterdam, NL | Kelembaban: 70%</span>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="text-dark fw-bold d-block">17°C</span>
                            <span class="text-secondary small" style="font-size: 0.7rem;"><i class="bi bi-wind me-1"></i>22 km/j</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Row: Global News & Exchange Rates & Recent Activity -->
    <div class="row g-4">
        <!-- Global News Card -->
        <div class="col-xl-6 col-lg-8">
            <div class="card p-4 h-100 border-0">
                <h5 class="fw-bold text-dark mb-2"><i class="bi bi-newspaper text-primary me-2"></i>Berita Logistik & Supply Chain Global</h5>
                <p class="text-secondary small mb-4">Umpan informasi terpercaya kondisi logistik internasional terhangat.</p>

                <div class="d-flex flex-column gap-3.5">
                    <!-- News 1 -->
                    <div class="row g-3 align-items-center pb-3 border-bottom">
                        <div class="col-auto">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: rgba(239, 68, 68, 0.08); color: var(--danger);">
                                <i class="bi bi-exclamation-triangle-fill fs-3"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge badge-danger" style="font-size: 0.65rem;">Krisis</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">🇨🇳 China | 17 Jul 2026</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">Badai Tropis Shanghai Menangguhkan Operasional Bongkar Muat Kontainer</h6>
                            <button class="btn btn-light btn-sm px-3" style="min-height: 44px;" onclick="viewNewsDetail('Badai Tropis Shanghai Menangguhkan Operasional Bongkar Muat Kontainer', 'Kondisi cuaca ekstrem memaksa otoritas pelabuhan Shanghai menghentikan kegiatan logistik demi keselamatan armada.')">Baca Berita</button>
                        </div>
                    </div>

                    <!-- News 2 -->
                    <div class="row g-3 align-items-center pb-3 border-bottom">
                        <div class="col-auto">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: rgba(245, 158, 11, 0.08); color: var(--warning);">
                                <i class="bi bi-truck fs-3"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge badge-warning" style="font-size: 0.65rem;">Hambatan</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">🇩🇪 Jerman | 16 Jul 2026</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">Penundaan Distribusi Kargo Darat Akibat Protes Serikat Pekerja Jerman</h6>
                            <button class="btn btn-light btn-sm px-3" style="min-height: 44px;" onclick="viewNewsDetail('Penundaan Distribusi Kargo Darat Akibat Protes Serikat Pekerja Jerman', 'Aksi pemogokan kerja di beberapa jalur rel distribusi menyebabkan penumpukan barang sementara di terminal darat.')">Baca Berita</button>
                        </div>
                    </div>

                    <!-- News 3 -->
                    <div class="row g-3 align-items-center">
                        <div class="col-auto">
                            <div class="rounded-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; background-color: rgba(34, 197, 94, 0.08); color: var(--success);">
                                <i class="bi bi-check-circle-fill fs-3"></i>
                            </div>
                        </div>
                        <div class="col">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge badge-success" style="font-size: 0.65rem;">Stabil</span>
                                <span class="text-secondary" style="font-size: 0.75rem;">🇮🇩 Indonesia | 15 Jul 2026</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-2" style="font-size: 0.9rem;">Tanjung Priok Meluncurkan Digitalisasi Gate Logistik Pintar</h6>
                            <button class="btn btn-light btn-sm px-3" style="min-height: 44px;" onclick="viewNewsDetail('Tanjung Priok Meluncurkan Digitalisasi Gate Logistik Pintar', 'Implementasi sistem IoT dan otomatisasi gerbang memangkas waktu tunggu antrean truk kontainer hingga 40%.')">Baca Berita</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exchange Rate & Recent Activity (Column 2) -->
        <div class="col-xl-6 col-lg-4">
            <div class="row g-4 h-100">
                <!-- Exchange Rate Widget -->
                <div class="col-xl-6 col-md-12">
                    <div class="card p-4 h-100 border-0">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-cash-stack text-success me-2"></i>Nilai Tukar Rupiah</h5>
                        <p class="text-secondary small mb-3">Kurs konversi mata uang dunia utama (IDR).</p>
                        
                        <div class="d-flex flex-column gap-2.5">
                            <!-- USD -->
                            <div class="p-2.5 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-dark me-2 small">USD</span>
                                    <span class="text-secondary small">Dolar AS</span>
                                </div>
                                <span class="text-success small fw-bold">Rp16.245,00</span>
                            </div>
                            <!-- EUR -->
                            <div class="p-2.5 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-dark me-2 small">EUR</span>
                                    <span class="text-secondary small">Euro</span>
                                </div>
                                <span class="text-success small fw-bold">Rp17.650,00</span>
                            </div>
                            <!-- JPY -->
                            <div class="p-2.5 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-dark me-2 small">JPY</span>
                                    <span class="text-secondary small">Yen Jepang</span>
                                </div>
                                <span class="text-success small fw-bold">Rp102,40</span>
                            </div>
                            <!-- SGD -->
                            <div class="p-2.5 border rounded-3 bg-light d-flex align-items-center justify-content-between" style="background-color: #F8FAFC !important;">
                                <div class="d-flex align-items-center">
                                    <span class="fw-bold text-dark me-2 small">SGD</span>
                                    <span class="text-secondary small">Dolar SG</span>
                                </div>
                                <span class="text-success small fw-bold">Rp12.050,00</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity Timeline -->
                <div class="col-xl-6 col-md-12">
                    <div class="card p-4 h-100 border-0">
                        <h5 class="fw-bold text-dark mb-2"><i class="bi bi-clock-history text-primary me-2"></i>Log Aktivitas Intelijen</h5>
                        <p class="text-secondary small mb-3">Timeline aktivitas logistik terhangat rantai pasok.</p>
                        
                        <div class="style-timeline" style="position: relative; padding-left: 20px;">
                            <!-- Timeline border line -->
                            <div style="position: absolute; left: 6px; top: 8px; bottom: 8px; width: 2px; background-color: #E2E8F0;"></div>
                            
                            <!-- Item 1 -->
                            <div class="position-relative mb-3.5">
                                <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--success); border: 2px solid #FFFFFF;"></div>
                                <div class="small">
                                    <span class="text-dark fw-bold d-block">Data Cuaca Diperbarui</span>
                                    <span class="text-secondary d-block" style="font-size: 0.725rem;">Port of Singapore dimuat via Open-Meteo.</span>
                                    <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>5 menit yang lalu</span>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="position-relative mb-3.5">
                                <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--danger); border: 2px solid #FFFFFF;"></div>
                                <div class="small">
                                    <span class="text-dark fw-bold d-block">Risiko Shanghai Meningkat</span>
                                    <span class="text-secondary d-block" style="font-size: 0.725rem;">Tingkat risiko Shanghai melonjak ke level Kritis (4.92).</span>
                                    <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>15 menit yang lalu</span>
                                </div>
                            </div>

                            <!-- Item 3 -->
                            <div class="position-relative">
                                <div class="position-absolute rounded-circle" style="left: -19px; top: 4px; width: 10px; height: 10px; background-color: var(--primary); border: 2px solid #FFFFFF;"></div>
                                <div class="small">
                                    <span class="text-dark fw-bold d-block">Pembaruan Berita Masuk</span>
                                    <span class="text-secondary d-block" style="font-size: 0.725rem;">Gate Logistik Pintar Tanjung Priok berhasil dimuat.</span>
                                    <span class="text-secondary small" style="font-size: 0.65rem;"><i class="bi bi-clock me-1"></i>1 jam yang lalu</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<!-- Node Detail Modal Component -->
<div class="modal fade" id="nodeDetailModal" tabindex="-1" aria-labelledby="nodeDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="nodeDetailModalLabel"><i class="bi bi-geo-alt-fill text-primary me-2"></i>Status Hub Rantai Pasok</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-3">
                    <span id="node-modal-flag" class="fs-1 d-block mb-1">🌍</span>
                    <h5 id="node-modal-country" class="fw-bold text-dark mb-1">Negara</h5>
                    <span id="node-modal-port" class="text-secondary small d-block mb-2">Nama Pelabuhan</span>
                    <span id="node-modal-risk" class="badge">Risiko</span>
                </div>
                
                <div class="border rounded-4 p-3 bg-light mt-3" style="background-color: #F8FAFC !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-secondary"><i class="bi bi-cloud-sun me-1"></i>Kondisi Cuaca</span>
                        <span id="node-modal-weather" class="text-dark fw-semibold">Cerah (30°C)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-secondary"><i class="bi bi-speedometer2 me-1"></i>Indeks Kerentanan</span>
                        <span id="node-modal-index" class="text-dark fw-semibold">0.00</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="text-secondary"><i class="bi bi-check2-all me-1"></i>Status Operasional</span>
                        <span id="node-modal-status" class="text-success fw-semibold">Normal</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('ports') }}" class="btn btn-primary">Buka Dashboard Pelabuhan</a>
            </div>
        </div>
    </div>
</div>

<!-- News Detail Modal Component -->
<div class="modal fade" id="newsDetailModal" tabindex="-1" aria-labelledby="newsDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header">
                <h5 class="modal-title fw-bold text-dark" id="newsDetailModalLabel"><i class="bi bi-newspaper text-primary me-2"></i>Berita Logistik</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body p-4">
                <h5 class="fw-bold text-dark mb-3" id="news-modal-title">Judul Berita</h5>
                <p class="text-secondary small mb-0" id="news-modal-body">Isi Berita...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Map Zooming Logic
    let mapScale = 1;
    function zoomMap(factor) {
        mapScale *= factor;
        if (mapScale < 0.6) mapScale = 0.6;
        if (mapScale > 3.0) mapScale = 3.0;
        document.getElementById('map-container').style.transform = `scale(${mapScale})`;
    }

    function resetMapZoom() {
        mapScale = 1;
        document.getElementById('map-container').style.transform = `scale(1)`;
    }

    // Map Hover Tooltip
    function showMapTooltip(event, node) {
        const tooltip = document.getElementById('map-tooltip');
        const country = node.getAttribute('data-country');
        const port = node.getAttribute('data-port');
        const risk = node.getAttribute('data-risk');
        const level = node.getAttribute('data-level');
        const weather = node.getAttribute('data-weather');

        let badgeClass = 'badge-success';
        if (level === 'high') badgeClass = 'badge-danger';
        if (level === 'medium') badgeClass = 'badge-warning';

        tooltip.innerHTML = `
            <div class="fw-bold text-dark mb-1">${country}</div>
            <div class="text-secondary small mb-1.5">${port}</div>
            <div class="d-flex align-items-center justify-content-between gap-2" style="font-size: 0.725rem;">
                <span class="badge ${badgeClass}">Risiko: ${risk}</span>
                <span class="text-secondary">${weather.split(' ')[0]}</span>
            </div>
        `;
        
        tooltip.style.display = 'block';
        
        // Position tooltip near cursor relative to map container
        const rect = event.currentTarget.getBoundingClientRect();
        const mapRect = document.querySelector('.bg-light').getBoundingClientRect();
        tooltip.style.left = (rect.left - mapRect.left + 15) + 'px';
        tooltip.style.top = (rect.top - mapRect.top - 60) + 'px';
    }

    function hideMapTooltip() {
        document.getElementById('map-tooltip').style.display = 'none';
    }

    // Map Click Modal Trigger
    function showNodeDetails(node) {
        const country = node.getAttribute('data-country');
        const port = node.getAttribute('data-port');
        const risk = node.getAttribute('data-risk');
        const level = node.getAttribute('data-level');
        const weather = node.getAttribute('data-weather');

        let flag = '🌍';
        if (country.includes('Indonesia')) flag = '🇮🇩';
        if (country.includes('Singapura')) flag = '🇸🇬';
        if (country.includes('China')) flag = '🇨🇳';
        if (country.includes('Belanda')) flag = '🇳🇱';
        if (country.includes('Amerika')) flag = '🇺🇸';
        if (country.includes('Brasil')) flag = '🇧🇷';
        if (country.includes('Afrika')) flag = '🇿🇦';

        let badgeClass = 'badge-success';
        let opStatus = 'Normal / Stabil';
        if (level === 'high') {
            badgeClass = 'badge-danger';
            opStatus = 'Tertunda / Terhambat';
        }
        if (level === 'medium') {
            badgeClass = 'badge-warning';
            opStatus = 'Hambatan Ringan';
        }

        document.getElementById('node-modal-flag').textContent = flag;
        document.getElementById('node-modal-country').textContent = country;
        document.getElementById('node-modal-port').textContent = port;
        
        const riskBadge = document.getElementById('node-modal-risk');
        riskBadge.textContent = `Indeks Risiko: ${risk}`;
        riskBadge.className = `badge ${badgeClass}`;

        document.getElementById('node-modal-weather').textContent = weather;
        document.getElementById('node-modal-index').textContent = `${risk} / 5.0`;
        
        const statusEl = document.getElementById('node-modal-status');
        statusEl.textContent = opStatus;
        statusEl.className = level === 'high' ? 'text-danger fw-semibold' : (level === 'medium' ? 'text-warning fw-semibold' : 'text-success fw-semibold');

        // Show Modal
        const modal = new bootstrap.Modal(document.getElementById('nodeDetailModal'));
        modal.show();
    }

    // Map Filters
    function filterMapNodes() {
        const continent = document.getElementById('map-region-filter').value;
        const riskLevel = document.getElementById('map-risk-filter').value;
        const nodes = document.querySelectorAll('.map-node');

        nodes.forEach(node => {
            const nodeCont = node.getAttribute('data-continent');
            const nodeRisk = node.getAttribute('data-level');

            const matchesCont = (continent === 'all' || nodeCont === continent);
            const matchesRisk = (riskLevel === 'all' || nodeRisk === riskLevel);

            if (matchesCont && matchesRisk) {
                node.style.display = 'block';
            } else {
                node.style.display = 'none';
            }
        });
    }

    // Chart Cursor Tracking Simulation
    function trackChartCursor(event) {
        const chartWrapper = event.currentTarget;
        const rect = chartWrapper.getBoundingClientRect();
        const mouseX = event.clientX - rect.left;
        
        const guideLine = document.getElementById('chart-guide-line');
        const tooltip = document.getElementById('chart-tooltip');

        // Check horizontal bounds for layout
        if (mouseX > 60 && mouseX < rect.width - 20) {
            guideLine.style.left = mouseX + 'px';
            guideLine.style.display = 'block';

            tooltip.style.left = (mouseX + 15) + 'px';
            tooltip.style.top = '40px';
            tooltip.style.display = 'block';

            // Calculate mock index value based on cursor position
            const ratio = (mouseX - 60) / (rect.width - 80);
            const daysAgo = Math.round(30 - ratio * 30);
            const mockIndex = (2.2 + Math.sin(ratio * 6.28) * 0.8 + ratio * 0.5).toFixed(2);

            tooltip.innerHTML = `
                <div class="text-secondary" style="font-size: 0.65rem;">H-${daysAgo} Hari Lalu</div>
                <div class="fw-bold text-dark">Indeks: ${mockIndex}</div>
            `;
        }
    }

    function hideChartTracker() {
        document.getElementById('chart-guide-line').style.display = 'none';
        document.getElementById('chart-tooltip').style.display = 'none';
    }

    // Refresh Dashboard mock animation
    function refreshDashboardData() {
        const btn = document.querySelector('.btn-refresh-all');
        btn.innerHTML = '<i class="bi bi-arrow-clockwise animate-spin me-2"></i>Segarkan...';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = '<i class="bi bi-arrow-clockwise me-2"></i>Segarkan Dasbor';
            btn.disabled = false;
            alert('Dasbor logistik rantai pasok global berhasil disegarkan!');
        }, 1200);
    }

    // Open News Detail Modal
    function viewNewsDetail(title, body) {
        document.getElementById('news-modal-title').textContent = title;
        document.getElementById('news-modal-body').textContent = body;
        
        const modal = new bootstrap.Modal(document.getElementById('newsDetailModal'));
        modal.show();
    }
</script>

<style>
    /* Styling map nodes */
    .map-node {
        cursor: pointer;
        stroke: #FFFFFF;
        stroke-width: 1.5;
        transition: all 0.2s ease-in-out;
    }
    
    .map-node:hover {
        stroke-width: 2.5;
        r: 13px !important;
    }

    .node-success {
        fill: var(--success);
    }
    
    .node-warning {
        fill: var(--warning);
    }

    .node-danger {
        fill: var(--danger);
    }

    /* Pulse animation for key node */
    .animate-pulse-node {
        animation: node-pulse-ring 1.8s infinite;
    }

    @keyframes node-pulse-ring {
        0% {
            stroke-width: 1.5;
            stroke: rgba(239, 68, 68, 0.4);
        }
        50% {
            stroke-width: 3.5;
            stroke: rgba(239, 68, 68, 0.8);
        }
        100% {
            stroke-width: 1.5;
            stroke: rgba(239, 68, 68, 0.4);
        }
    }

    /* Animation class */
    .animate-spin {
        animation: spin 1s linear infinite;
        display: inline-block;
    }
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
</style>
@endsection
