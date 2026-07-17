<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan (404) - SupplyChain Platform</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #2563EB;
            --background: #F8FAFC;
            --radius-custom: 16px;
        }
        body {
            background-color: var(--background);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
    </style>
</head>
<body>

<div class="card p-5 border-0 shadow-lg text-center" style="max-width: 460px; width: 100%; border-radius: var(--radius-custom) !important; background-color: #FFFFFF;">
    <div class="mb-3 d-flex justify-content-center">
        <div class="p-4 rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
            <i class="bi bi-compass-fill fs-1" style="color: #F59E0B;"></i>
        </div>
    </div>
    
    <h1 class="fw-bold text-warning mb-1" style="font-size: 4rem; color: #F59E0B !important;">404</h1>
    <h4 class="fw-bold text-dark mb-2">Halaman Tidak Ditemukan</h4>
    <p class="text-secondary small mb-4">Halaman satelit logistik atau rute navigasi yang Anda cari tidak tersedia atau dipindahkan.</p>
    
    <a href="{{ route('dashboard') }}" class="btn btn-primary w-100 fw-semibold d-flex align-items-center justify-content-center" style="min-height: 44px; border-radius: 10px;">
        Kembali ke Dashboard
    </a>
</div>

</body>
</html>
