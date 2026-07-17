<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesi Berakhir - SupplyChain Platform</title>
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

<x-auth-card title="Sesi Anda Berakhir" subtitle="Untuk menjaga keamanan data rantai pasok, koneksi Anda diputus setelah tidak aktif selama beberapa saat.">
    
    <div class="d-flex flex-column gap-3">
        <!-- SVG clock illustration -->
        <div class="my-4 text-center d-flex justify-content-center">
            <div class="p-4 rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <i class="bi bi-clock-history fs-1"></i>
            </div>
        </div>

        <p class="text-secondary small mb-3">Silakan masuk kembali untuk melanjutkan akses dashboard pengawasan satelit logistik.</p>

        <a href="{{ route('login') }}" class="btn btn-primary w-100 fw-semibold d-flex align-items-center justify-content-center" style="min-height: 44px; border-radius: 10px;">
            Masuk Kembali (Login Ulang)
        </a>
    </div>
</x-auth-card>

</body>
</html>
