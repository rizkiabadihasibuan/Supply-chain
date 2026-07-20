@extends('layouts.user.app')

@section('title', 'User Profile & Settings - SupplyChain Platform')

@section('content')
@php
    $u = Auth::user() ?? (object)[
        'name' => 'iky',
        'email' => 'iky@gmail.com',
        'role' => 'Senior Supply Chain Analyst',
        'created_at' => now(),
        'phone' => '+62 812-3456-7890',
        'company' => 'Global Logistics Group',
        'location' => 'Jakarta, Indonesia'
    ];
@endphp

<div class="container-fluid p-0 fade-in-up">

    <!-- Header & Breadcrumb -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill me-1"></i>Beranda</a></li>
                        <li class="breadcrumb-item active" aria-current="page">User Profile & Settings</li>
                    </ol>
                </nav>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h3 class="fw-bold text-dark mb-1"><i class="bi bi-person-badge-fill text-primary me-2"></i>User Profile & Settings</h3>
                        <p class="text-secondary small mb-0">Kelola informasi akun, keamanan password, preferensi aplikasi, dan log aktivitas pengguna.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN PROFILE HEADER CARD -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm" style="background: linear-gradient(135deg, #1E293B 0%, #0F172A 100%); color: #FFFFFF; border-radius: 16px;">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                    
                    <div class="d-flex align-items-center gap-4">
                        <div class="position-relative">
                            <div class="d-flex align-items-center justify-content-center bg-primary text-white fw-bold rounded-circle border border-3 border-light shadow-sm" style="width: 84px; height: 84px; font-size: 2.2rem;">
                                {{ strtoupper(substr($u->name ?? 'I', 0, 1)) }}
                            </div>
                            <span class="position-absolute bottom-0 end-0 p-1.5 bg-success border border-2 border-white rounded-circle" title="Status Online"></span>
                        </div>

                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <h3 class="fw-bold text-white mb-0">{{ $u->name ?? 'iky' }}</h3>
                                <span class="badge bg-primary px-3 py-1.5" style="font-size: 0.78rem;">Senior Supply Chain Analyst</span>
                            </div>
                            <span class="text-white-50 d-block small mb-2"><i class="bi bi-envelope me-1.5"></i>{{ $u->email ?? 'iky@gmail.com' }}</span>
                            <div class="d-flex align-items-center gap-3 text-white-50 extra-small" style="font-size: 0.78rem;">
                                <span><i class="bi bi-geo-alt me-1"></i>Jakarta, Indonesia</span>
                                <span><i class="bi bi-building me-1"></i>Global Supply Chain Risk Intelligence</span>
                                <span><i class="bi bi-shield-check text-success me-1"></i>Akun Terverifikasi</span>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-light btn-sm fw-semibold shadow-sm px-3" onclick="showTab('edit-profile')" style="min-height: 40px;">
                            <i class="bi bi-pencil-square me-1"></i> Edit Profil
                        </button>
                        <button class="btn btn-outline-light btn-sm px-3" onclick="showTab('security')" style="min-height: 40px;">
                            <i class="bi bi-key me-1"></i> Ubah Password
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- PROFILE NAVIGATION TABS -->
    <div class="row g-4 mb-4">
        <div class="col-12">
            <div class="card p-2 border-0 shadow-sm" style="border-radius: 12px; background: #F8FAFC;">
                <div class="nav nav-pills nav-fill gap-2" id="profileTabs">
                    <button class="nav-link active fw-semibold" id="tab-overview" onclick="switchProfileTab('overview')">
                        <i class="bi bi-grid-1x2-fill me-1.5"></i> Ikhtisar Akun
                    </button>
                    <button class="nav-link fw-semibold" id="tab-edit-profile" onclick="switchProfileTab('edit-profile')">
                        <i class="bi bi-person-gear me-1.5"></i> Informasi Pribadi
                    </button>
                    <button class="nav-link fw-semibold" id="tab-security" onclick="switchProfileTab('security')">
                        <i class="bi bi-shield-lock me-1.5"></i> Keamanan & Password
                    </button>
                    <button class="nav-link fw-semibold" id="tab-notification" onclick="switchProfileTab('notification')">
                        <i class="bi bi-bell me-1.5"></i> Notifikasi Risk Alert
                    </button>
                    <button class="nav-link fw-semibold" id="tab-activity" onclick="switchProfileTab('activity')">
                        <i class="bi bi-clock-history me-1.5"></i> Log Aktivitas
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- TAB CONTENTS -->

    <!-- 1. OVERVIEW SHEET -->
    <div id="sheet-overview" class="profile-sheet row g-4 mb-4">
        <!-- System Access Stats -->
        <div class="col-lg-8">
            <div class="card p-4 border-0 shadow-sm h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-info-circle-fill text-primary me-2"></i>Rincian Akses & Hak Akses Platform</h5>
                
                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <span class="text-secondary small d-block mb-1">Peran Pengguna</span>
                            <h6 class="fw-bold text-dark mb-0">Analyst Administrator</h6>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <span class="text-secondary small d-block mb-1">Status Keanggotaan</span>
                            <h6 class="fw-bold text-success mb-0">Aktif (Enterprise Plan)</h6>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <span class="text-secondary small d-block mb-1">Cakupan Pantauan</span>
                            <h6 class="fw-bold text-dark mb-0">250 Negara (Full Access)</h6>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="p-3 rounded-3 border bg-light">
                            <span class="text-secondary small d-block mb-1">Terakhir Login</span>
                            <h6 class="fw-bold text-dark mb-0">Hari Ini, 23.33 WIB</h6>
                        </div>
                    </div>
                </div>

                <h6 class="fw-bold text-dark mb-2">Izin Fitur Aktif:</h6>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2"><i class="bi bi-check2 me-1"></i> Global Country Monitoring</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2"><i class="bi bi-check2 me-1"></i> Global Weather Radar</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2"><i class="bi bi-check2 me-1"></i> Exchange Rate Center</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2"><i class="bi bi-check2 me-1"></i> GNews Intelligence</span>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-3 py-2"><i class="bi bi-check2 me-1"></i> Risk Score Engine</span>
                </div>
            </div>
        </div>

        <!-- Security Summary -->
        <div class="col-lg-4">
            <div class="card p-4 border-0 shadow-sm h-100">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-success me-2"></i>Status Keamanan Akun</h5>
                <div class="p-3 rounded-3 bg-success bg-opacity-10 border border-success border-opacity-25 mb-3">
                    <div class="d-flex align-items-center gap-2 text-success fw-bold mb-1">
                        <i class="bi bi-check-circle-fill"></i> Proteksi Akun Optimal
                    </div>
                    <p class="text-secondary small mb-0" style="font-size: 0.8rem;">Sandi Anda telah diamankan menggunakan enkripsi Bcrypt hashing.</p>
                </div>
                <div class="d-flex flex-column gap-2.5">
                    <div class="d-flex justify-content-between align-items-center small py-2 border-bottom">
                        <span class="text-secondary">Autentikasi 2-Faktor</span>
                        <span class="badge bg-secondary">Nonaktif</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small py-2 border-bottom">
                        <span class="text-secondary">Perangkat Terdaftar</span>
                        <span class="fw-bold text-dark">Windows (Opera/Chrome)</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small py-2">
                        <span class="text-secondary">IP Address Sesi</span>
                        <span class="fw-bold text-dark">127.0.0.1 (Localhost)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. EDIT PROFILE SHEET -->
    <div id="sheet-edit-profile" class="profile-sheet row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-person-gear text-primary me-2"></i>Informasi Pribadi & Kontak</h5>
                <form onsubmit="saveProfileInfo(event)">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Nama Lengkap</label>
                            <input type="text" class="form-control" value="{{ $u->name ?? 'iky' }}" required style="min-height: 44px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Alamat Email</label>
                            <input type="email" class="form-control" value="{{ $u->email ?? 'iky@gmail.com' }}" required style="min-height: 44px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Nomor Telepon</label>
                            <input type="text" class="form-control" value="+62 812-3456-7890" style="min-height: 44px;">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark">Perusahaan / Organisasi</label>
                            <input type="text" class="form-control" value="Global Supply Chain Risk Intelligence" style="min-height: 44px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Lokasi / Alamat Operasional</label>
                            <input type="text" class="form-control" value="Jakarta, Indonesia" style="min-height: 44px;">
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary px-4 fw-semibold" style="min-height: 42px;">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan Profil
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 3. SECURITY SHEET -->
    <div id="sheet-security" class="profile-sheet row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock text-danger me-2"></i>Pembaruan Password & Keamanan</h5>
                <form onsubmit="savePassword(event)">
                    <div class="row g-3" style="max-width: 600px;">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Password Saat Ini</label>
                            <input type="password" class="form-control" placeholder="••••••••" required style="min-height: 44px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Password Baru</label>
                            <input type="password" class="form-control" placeholder="••••••••" required style="min-height: 44px;">
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark">Konfirmasi Password Baru</label>
                            <input type="password" class="form-control" placeholder="••••••••" required style="min-height: 44px;">
                        </div>
                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-danger px-4 fw-semibold" style="min-height: 42px;">
                                <i class="bi bi-key-fill me-1"></i> Perbarui Password Akun
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- 4. NOTIFICATION SHEET -->
    <div id="sheet-notification" class="profile-sheet row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bell text-warning me-2"></i>Pengaturan Notifikasi & Alert Risk</h5>
                <div class="d-flex flex-column gap-3" style="max-width: 700px;">
                    <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 bg-light">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Email Alert Peringatan Badai & Cuaca Ekstrem</h6>
                            <p class="text-secondary extra-small mb-0">Kirim email otomatis jika terjadi indikator badai di negara terpilih.</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked style="width: 44px; height: 24px;">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 bg-light">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Notifikasi Fluktuasi Kurs Mata Uang > 2.0%</h6>
                            <p class="text-secondary extra-small mb-0">Pemberitahuan perubahan signifikan nilai tukar valuta asing.</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" checked style="width: 44px; height: 24px;">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 bg-light">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Laporan Intelijen GNews Mingguan</h6>
                            <p class="text-secondary extra-small mb-0">Rangkuman ringkasan berita rantai pasok global setiap hari Senin.</p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" style="width: 44px; height: 24px;">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 5. ACTIVITY LOG SHEET -->
    <div id="sheet-activity" class="profile-sheet row g-4 mb-4" style="display: none;">
        <div class="col-12">
            <div class="card p-4 border-0 shadow-sm">
                <h5 class="fw-bold text-dark mb-3"><i class="bi bi-clock-history text-info me-2"></i>Log Aktivitas Pengguna Terakhir</h5>
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Aktivitas</th>
                                <th>Modul Fitur</th>
                                <th>Waktu Akses</th>
                                <th>IP Address</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-semibold">Login Berhasil</td>
                                <td>Autentikasi Akun</td>
                                <td>Hari Ini, 23:33 WIB</td>
                                <td>127.0.0.1</td>
                                <td><span class="badge bg-success">Berhasil</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Akses Global Risk Analysis</td>
                                <td>Risk Analysis Module</td>
                                <td>Hari Ini, 23:18 WIB</td>
                                <td>127.0.0.1</td>
                                <td><span class="badge bg-success">Berhasil</span></td>
                            </tr>
                            <tr>
                                <td class="fw-semibold">Cetak Laporan PDF Negara</td>
                                <td>Export Engine</td>
                                <td>Hari Ini, 23:05 WIB</td>
                                <td>127.0.0.1</td>
                                <td><span class="badge bg-success">Berhasil</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function switchProfileTab(tabName) {
        document.querySelectorAll('.profile-sheet').forEach(sheet => {
            sheet.style.display = 'none';
        });

        document.querySelectorAll('#profileTabs .nav-link').forEach(link => {
            link.classList.remove('active');
        });

        const activeSheet = document.getElementById('sheet-' + tabName);
        if (activeSheet) {
            activeSheet.style.display = 'flex';
        }

        const activeTab = document.getElementById('tab-' + tabName);
        if (activeTab) {
            activeTab.classList.add('active');
        }
    }

    function showTab(tabName) {
        switchProfileTab(tabName);
    }

    function saveProfileInfo(e) {
        e.preventDefault();
        alert('Informasi profil Anda telah berhasil diperbarui!');
    }

    function savePassword(e) {
        e.preventDefault();
        alert('Password akun Anda telah berhasil diperbarui!');
    }
</script>
@endsection
