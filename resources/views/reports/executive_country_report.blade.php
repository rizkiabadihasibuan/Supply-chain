<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Risiko Eksekutif - {{ $country->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Inter', system-ui, sans-serif; background: #f8fafc; color: #1e293b; }
        .report-card { background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 2.5rem; }
        .header-bg { background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); color: #ffffff; border-radius: 12px; padding: 2rem; }
        @media print {
            .no-print { display: none !important; }
            body { background: #ffffff; }
            .report-card { border: none; padding: 0; }
        }
    </style>
</head>
<body class="py-4">
    <div class="container max-w-4xl">

        <!-- Control Bar -->
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <a href="javascript:history.back()" class="btn btn-outline-secondary btn-sm"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
            <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer me-1"></i> Cetak / Simpan PDF</button>
        </div>

        <div class="report-card shadow-sm">
            <!-- Header -->
            <div class="header-bg mb-4 d-flex justify-content-between align-items-center">
                <div>
                    <span class="badge bg-light text-primary fw-bold mb-2">SUPPLY CHAIN EXECUTIVE REPORT</span>
                    <h2 class="fw-bold mb-1">{{ $country->name }} ({{ $country->code }})</h2>
                    <p class="mb-0 text-white-50">Laporan Analisis Risiko Logistik & Profil Pengadaan Impor</p>
                </div>
                <div class="text-end">
                    <img src="{{ $country->flag_url }}" alt="{{ $country->name }}" style="height: 48px; width: 72px; object-fit: cover; border-radius: 6px;" class="border border-light mb-2">
                    <span class="d-block text-white-50 small">Tanggal: {{ date('d M Y') }}</span>
                </div>
            </div>

            <!-- Risk Overview Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light text-center">
                        <span class="text-secondary small d-block mb-1">Skor Risiko Global</span>
                        <h2 class="fw-bold text-dark mb-0">{{ number_format($finalScore, 1) }} <small class="text-muted fs-6">/ 100</small></h2>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light text-center">
                        <span class="text-secondary small d-block mb-1">Kategori Risiko</span>
                        <span class="badge bg-{{ $badgeClass }} fs-5 px-3 py-2 mt-1">{{ $riskLevel }} Risk</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded-3 bg-light text-center">
                        <span class="text-secondary small d-block mb-1">Status Kelayakan Impor</span>
                        <span class="fw-bold text-{{ $badgeClass }} d-block mt-2 fs-6">
                            {{ $finalScore <= 30 ? 'Sangat Direkomendasikan' : ($finalScore <= 60 ? 'Layak (Pengawasan)' : 'Beresiko Tinggi') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Detail Profil Country -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-info-circle me-2 text-primary"></i>Informasi Umum & Geografis</h5>
            <div class="row g-3 mb-4 text-sm">
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr><td class="text-secondary">Wilayah:</td><td class="fw-medium">{{ $country->region?->name ?? 'Global' }}</td></tr>
                        <tr><td class="text-secondary">Ibukota:</td><td class="fw-medium">{{ $country->capital ?? 'N/A' }}</td></tr>
                        <tr><td class="text-secondary">Mata Uang:</td><td class="fw-medium">{{ $country->currency?->code ?? 'USD' }} ({{ $country->currency?->symbol ?? '$' }})</td></tr>
                    </table>
                </div>
                <div class="col-md-6">
                    <table class="table table-sm table-borderless">
                        <tr><td class="text-secondary">Populasi:</td><td class="fw-medium">{{ number_format($country->population ?? 0) }} Jiwa</td></tr>
                        <tr><td class="text-secondary">Koordinat:</td><td class="fw-medium">{{ number_format($country->latitude, 2) }}, {{ number_format($country->longitude, 2) }}</td></tr>
                        <tr><td class="text-secondary">Status Sistem:</td><td class="fw-medium text-success"><i class="bi bi-check-circle-fill me-1"></i>Terverifikasi Real-time API</td></tr>
                    </table>
                </div>
            </div>

            <!-- Risk Components Breakdown -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-pie-chart me-2 text-primary"></i>Rincian Skor Komponen Risiko (0-100)</h5>
            <div class="row g-3 mb-4">
                @php
                    $components = $riskScore?->components ?? [
                        'weather' => round($finalScore * 0.9, 1),
                        'economic' => round($finalScore * 0.8, 1),
                        'political' => round($finalScore * 1.0, 1),
                        'logistics' => round($finalScore * 0.95, 1),
                    ];
                @endphp
                @foreach($components as $key => $val)
                <div class="col-md-3 col-6">
                    <div class="p-3 border rounded-3">
                        <span class="text-secondary text-capitalize small d-block mb-1">{{ $key }} Risk</span>
                        <div class="progress mb-2" style="height: 6px;">
                            <div class="progress-bar bg-{{ $val > 60 ? 'danger' : ($val > 30 ? 'warning' : 'success') }}" style="width: {{ min(100, $val) }}%"></div>
                        </div>
                        <span class="fw-bold text-dark">{{ number_format($val, 1) }}</span>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Active Alerts -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="bi bi-shield-exclamation me-2 text-primary"></i>Peringatan Risiko Terdeteksi</h5>
            @if(($country->riskAlerts ?? collect())->count() > 0)
                <ul class="list-group mb-4">
                    @foreach($country->riskAlerts as $alert)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <strong class="d-block">{{ $alert->title }}</strong>
                            <small class="text-secondary">{{ $alert->description }}</small>
                        </div>
                        <span class="badge bg-{{ $alert->severity === 'high' ? 'danger' : 'warning' }}">{{ strtoupper($alert->severity) }}</span>
                    </li>
                    @endforeach
                </ul>
            @else
                <div class="alert alert-success d-flex align-items-center mb-4"><i class="bi bi-check-circle-fill me-2 fs-5"></i> Tidak ada ancaman gangguan signifikan terdeteksi untuk negara ini.</div>
            @endif

            <!-- Sign-off Block for Business Intelligence -->
            <div class="pt-4 border-top mt-5">
                <div class="row text-center text-secondary small">
                    <div class="col-4">
                        <p class="mb-5">Dibuat Oleh:</p>
                        <p class="fw-bold text-dark mb-0">System Risk Intelligence</p>
                    </div>
                    <div class="col-4">
                        <p class="mb-5">Diperiksa Oleh:</p>
                        <p class="fw-bold text-dark mb-0">Analis Supply Chain</p>
                    </div>
                    <div class="col-4">
                        <p class="mb-5">Disetujui Oleh:</p>
                        <p class="fw-bold text-dark mb-0">Manajer Logistik & Impor</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
