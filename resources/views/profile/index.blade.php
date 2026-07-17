@extends('layouts.app')

@section('title', 'Profil & Pengaturan - SupplyChain Platform')

@section('content')
<!-- Page Header Component -->
<x-page-header title="Profil & Pengaturan" subtitle="Kelola informasi akun, preferensi aplikasi, keamanan, dan pengaturan pribadi Anda." :breadcrumbs="['My Profile' => '#']" />

<!-- Skeleton Loading wrapper -->
<div id="skeleton-container" style="display: none;">
    <x-loading-state type="card" count="4" height="240px" />
</div>

<!-- Empty State Component -->
<div id="empty-state-container" style="display: none;">
    <x-empty-state title="Belum ada data pengaturan." description="Tidak ada parameter profil yang ditemukan dalam radar satelit." onclick="resetFilters()" />
</div>

<!-- Error State Component -->
<div id="error-state-container" style="display: none;">
    <x-error-state title="Gagal Memuat Pengaturan." description="Satelit data profil pengguna sedang sibuk. Silakan coba kembali." onclick="retryFromError()" />
</div>

<!-- Main Content Grid -->
<div id="main-content-grid" class="row g-4">
    <!-- Left Column: Settings Sidebar (turns to accordion on mobile via custom css/accordion wrapper) -->
    <div class="col-12 col-md-4 col-lg-3">
        <div class="d-none d-md-block">
            <x-settings-sidebar activeTab="overview" />
        </div>
        
        <!-- Mobile Sidebar Accordion -->
        <div class="accordion d-block d-md-none mb-3" id="settingsAccordion">
            <div class="accordion-item border-0 card">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold text-primary collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSettingsMenu" aria-expanded="false" aria-controls="collapseSettingsMenu" style="min-height: 44px; border-radius: 12px;">
                        <i class="bi bi-gear-fill me-2"></i>Pilih Tab Pengaturan
                    </button>
                </h2>
                <div id="collapseSettingsMenu" class="accordion-collapse collapse" data-bs-parent="#settingsAccordion">
                    <div class="accordion-body p-2 d-flex flex-column gap-1.5">
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3 active" onclick="switchSettingsTab('overview', this); collapseAccordion()">
                            <i class="bi bi-person-fill me-2.5"></i>Ringkasan Akun
                        </button>
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3" onclick="switchSettingsTab('edit-profile', this); collapseAccordion()">
                            <i class="bi bi-pencil-square me-2.5"></i>Ubah Profil
                        </button>
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3" onclick="switchSettingsTab('security', this); collapseAccordion()">
                            <i class="bi bi-shield-lock-fill me-2.5"></i>Keamanan Sandi
                        </button>
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3" onclick="switchSettingsTab('notification', this); collapseAccordion()">
                            <i class="bi bi-bell-fill me-2.5"></i>Notifikasi & Alert
                        </button>
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3" onclick="switchSettingsTab('appearance', this); collapseAccordion()">
                            <i class="bi bi-palette-fill me-2.5"></i>Tema & Tampilan
                        </button>
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3" onclick="switchSettingsTab('language', this); collapseAccordion()">
                            <i class="bi bi-translate me-2.5"></i>Bahasa (Language)
                        </button>
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3" onclick="switchSettingsTab('regional', this); collapseAccordion()">
                            <i class="bi bi-globe me-2.5"></i>Regional & Format
                        </button>
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3" onclick="switchSettingsTab('session', this); collapseAccordion()">
                            <i class="bi bi-pc-display-horizontal me-2.5"></i>Sesi Perangkat
                        </button>
                        <button class="settings-menu-item text-start border-0 py-2.5 px-3 rounded-3" onclick="switchSettingsTab('privacy', this); collapseAccordion()">
                            <i class="bi bi-eye-slash-fill me-2.5"></i>Privasi Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Center Column: Content Sheets Area -->
    <div class="col-12 col-md-8 col-lg-6">
        <div class="d-flex flex-column gap-4">
            
            <!-- FEEDBACK AREA -->
            <div id="settings-feedback-area" style="display: none;">
                <x-validation-message type="success" message="Pengaturan berhasil disimpan!" />
            </div>

            <!-- SHEET 1: Overview -->
            <div class="settings-tab-sheet" id="sheet-overview">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-person-fill text-primary me-2"></i>Ringkasan Akun Pengguna</h5>
                    
                    <!-- Avatar Component -->
                    <x-profile-avatar />

                    <hr class="text-secondary opacity-25 my-4">

                    <div class="d-flex flex-column gap-3.5" style="font-size: 0.875rem;">
                        <div class="row">
                            <div class="col-sm-4 text-secondary">Nama Lengkap:</div>
                            <div class="col-sm-8 text-dark fw-bold">Administrator</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-secondary">Alamat Email:</div>
                            <div class="col-sm-8 text-dark fw-semibold">admin@supplychain.com</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-secondary">Tingkat Hak Akses:</div>
                            <div class="col-sm-8"><span class="badge bg-primary">Administrator</span></div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-secondary">Status Akun:</div>
                            <div class="col-sm-8 text-success fw-bold">● Aktif</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-secondary">Tanggal Bergabung:</div>
                            <div class="col-sm-8 text-dark">17 Juli 2026</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4 text-secondary">Login Terakhir:</div>
                            <div class="col-sm-8 text-dark">Baru Saja</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHEET 2: Edit Profile -->
            <div class="settings-tab-sheet" id="sheet-edit-profile" style="display: none;">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pencil-square text-primary me-2"></i>Ubah Informasi Profil</h5>
                    
                    <x-profile-form id="form-edit-profile">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label for="fullname" class="form-label small fw-semibold text-secondary mb-1.5">Nama Lengkap</label>
                                <input type="text" id="fullname" class="form-control" value="Administrator" style="min-height: 44px;" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="username" class="form-label small fw-semibold text-secondary mb-1.5">Username</label>
                                <input type="text" id="username" class="form-control" value="admin_center" style="min-height: 44px;" required>
                            </div>
                        </div>
                        
                        <div class="row g-3 mt-1">
                            <div class="col-sm-6">
                                <label for="email" class="form-label small fw-semibold text-secondary mb-1.5">Alamat Email</label>
                                <input type="email" id="email" class="form-control" value="admin@supplychain.com" style="min-height: 44px;" required>
                            </div>
                            <div class="col-sm-6">
                                <label for="phone" class="form-label small fw-semibold text-secondary mb-1.5">Nomor Telepon</label>
                                <input type="text" id="phone" class="form-control" value="+62 812-3456-7890" style="min-height: 44px;">
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-sm-6">
                                <label for="company" class="form-label small fw-semibold text-secondary mb-1.5">Nama Perusahaan</label>
                                <input type="text" id="company" class="form-control" value="Mitra Logistik Global PT" style="min-height: 44px;">
                            </div>
                            <div class="col-sm-6">
                                <label for="designation" class="form-label small fw-semibold text-secondary mb-1.5">Jabatan Pekerjaan</label>
                                <input type="text" id="designation" class="form-control" value="Kepala Operasional Command Center" style="min-height: 44px;">
                            </div>
                        </div>

                        <div class="mt-2.5">
                            <label for="bio" class="form-label small fw-semibold text-secondary mb-1.5">Biografi Ringkas</label>
                            <textarea id="bio" class="form-control" rows="3">Penanggung jawab koordinasi satelit mitigasi risiko rantai pasok maritim.</textarea>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Simpan Perubahan</button>
                        </div>
                    </x-profile-form>
                </div>
            </div>

            <!-- SHEET 3: Security -->
            <div class="settings-tab-sheet" id="sheet-security" style="display: none;">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-shield-lock-fill text-primary me-2"></i>Keamanan Kata Sandi</h5>
                    
                    <x-profile-form id="form-security">
                        <div class="mb-3.5">
                            <label for="current_password" class="form-label small fw-semibold text-secondary mb-1.5">Kata Sandi Saat Ini</label>
                            <input type="password" id="current_password" class="form-control" placeholder="••••••••" style="min-height: 44px;" required>
                        </div>

                        <div class="mb-3.5">
                            <label for="new_password" class="form-label small fw-semibold text-secondary mb-1.5">Kata Sandi Baru</label>
                            <input type="password" id="new_password" class="form-control" placeholder="••••••••" style="min-height: 44px;" required>
                        </div>

                        <div class="mb-3.5">
                            <label for="confirm_password" class="form-label small fw-semibold text-secondary mb-1.5">Konfirmasi Kata Sandi Baru</label>
                            <input type="password" id="confirm_password" class="form-control" placeholder="••••••••" style="min-height: 44px;" required>
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Perbarui Sandi</button>
                        </div>
                    </x-profile-form>
                </div>
            </div>

            <!-- SHEET 4: Notification Settings -->
            <div class="settings-tab-sheet" id="sheet-notification" style="display: none;">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-bell-fill text-primary me-2"></i>Preferensi Notifikasi & Alert</h5>
                    <p class="text-secondary small mb-4">Tentukan jenis perubahan jalur maritim yang ingin dikirimkan via email atau alert sistem.</p>

                    <x-profile-form id="form-notification">
                        <x-toggle-switch id="email_notify" label="Notifikasi Email Harian" :checked="true" />
                        <x-toggle-switch id="risk_alert" label="Alert Level Risiko Kritis" :checked="true" />
                        <x-toggle-switch id="weather_alert" label="Laporan Cuaca Ekstrem Pelabuhan" :checked="true" />
                        <x-toggle-switch id="currency_alert" label="Volatilitas Valuta Asing Dagang" :checked="false" />
                        <x-toggle-switch id="news_alert" label="Umpan Berita Logistik Penting" :checked="true" />
                        <x-toggle-switch id="port_alert" label="Dwelling Time Gerbang Port" :checked="false" />
                        <x-toggle-switch id="system_alert" label="Laporan Kinerja Sistem Bulanan" :checked="false" />

                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Simpan Preferensi</button>
                        </div>
                    </x-profile-form>
                </div>
            </div>

            <!-- SHEET 5: Appearance (Theme) -->
            <div class="settings-tab-sheet" id="sheet-appearance" style="display: none;">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-palette-fill text-primary me-2"></i>Tema & Tampilan Aplikasi</h5>
                    <p class="text-secondary small mb-4">Ubah warna tata letak dasbor agar nyaman di mata Anda.</p>

                    <div class="row g-3">
                        <div class="col-6 col-sm-4">
                            <div class="border rounded-4 p-3 text-center theme-selector-card active" onclick="switchThemeMode('light', this)" style="cursor: pointer;">
                                <div class="bg-light border mb-2 rounded-3" style="height: 60px;"></div>
                                <span class="small fw-semibold text-dark">Mode Terang (Light)</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="border rounded-4 p-3 text-center theme-selector-card" onclick="switchThemeMode('dark', this)" style="cursor: pointer;">
                                <div class="bg-dark border border-secondary mb-2 rounded-3" style="height: 60px;"></div>
                                <span class="small fw-semibold text-dark">Mode Gelap (Dark)</span>
                            </div>
                        </div>
                        <div class="col-6 col-sm-4">
                            <div class="border rounded-4 p-3 text-center theme-selector-card" onclick="switchThemeMode('system', this)" style="cursor: pointer;">
                                <div class="bg-secondary border mb-2 rounded-3" style="height: 60px;"></div>
                                <span class="small fw-semibold text-dark">Sistem Browser</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHEET 6: Language -->
            <div class="settings-tab-sheet" id="sheet-language" style="display: none;">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-translate text-primary me-2"></i>Bahasa Antarmuka (Language)</h5>

                    <x-profile-form id="form-language">
                        <x-dropdown-select id="select_language" label="Bahasa Utama">
                            <option value="id" selected>Bahasa Indonesia</option>
                            <option value="en">English (US)</option>
                        </x-dropdown-select>

                        <div class="mt-4 pt-3">
                            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Simpan Bahasa</button>
                        </div>
                    </x-profile-form>
                </div>
            </div>

            <!-- SHEET 7: Regional Settings -->
            <div class="settings-tab-sheet" id="sheet-regional" style="display: none;">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-globe text-primary me-2"></i>Regional & Lokalisasi Format</h5>

                    <x-profile-form id="form-regional">
                        <x-dropdown-select id="select_timezone" label="Zona Waktu (Timezone)">
                            <option value="wib" selected>Asia/Jakarta (WIB) - GMT+07:00</option>
                            <option value="sgt">Asia/Singapore (SGT) - GMT+08:00</option>
                            <option value="utc">Coordinated Universal Time (UTC) - GMT+00:00</option>
                        </x-dropdown-select>

                        <x-dropdown-select id="select_currency" label="Mata Uang Acuan (Base Currency)">
                            <option value="idr" selected>IDR (Rp) - Rupiah Indonesia</option>
                            <option value="usd">USD ($) - Dollar Amerika Serikat</option>
                            <option value="eur">EUR (€) - Euro Eropa</option>
                        </x-dropdown-select>

                        <x-dropdown-select id="select_date_format" label="Format Tanggal">
                            <option value="d-m-Y" selected>DD-MM-YYYY (Contoh: 18-07-2026)</option>
                            <option value="Y-m-d">YYYY-MM-DD (Contoh: 2026-07-18)</option>
                        </x-dropdown-select>

                        <x-dropdown-select id="select_number_format" label="Format Angka Desimal">
                            <option value="dot" selected>Titik Desimal (Contoh: 1,000.50)</option>
                            <option value="comma">Koma Desimal (Contoh: 1.000,50)</option>
                        </x-dropdown-select>

                        <div class="mt-4 pt-3">
                            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Simpan Format</button>
                        </div>
                    </x-profile-form>
                </div>
            </div>

            <!-- SHEET 8: Session devices -->
            <div class="settings-tab-sheet" id="sheet-session" style="display: none;">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pc-display-horizontal text-primary me-2"></i>Sesi Log Perangkat Terhubung</h5>
                    <p class="text-secondary small mb-4">Pengawasan koneksi perangkat aktif demi keamanan data komparasi.</p>

                    <div class="d-flex flex-column gap-3">
                        <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3" style="background-color: #FAFCFF !important;">
                            <div class="fs-2 text-primary"><i class="bi bi-laptop"></i></div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.875rem;">Windows PC - Chrome (Sesi Saat Ini)</span>
                                <span class="text-secondary small d-block">Jakarta, Indonesia | IP: 192.168.1.45</span>
                            </div>
                            <span class="badge bg-success ms-auto">Aktif</span>
                        </div>

                        <!-- Other device placeholder -->
                        <div class="p-3 border rounded-3 bg-light d-flex align-items-center gap-3 opacity-75" style="background-color: #FAFCFF !important;">
                            <div class="fs-2 text-secondary"><i class="bi bi-phone"></i></div>
                            <div>
                                <span class="fw-bold text-dark d-block" style="font-size: 0.875rem;">iPhone 14 - Safari (Mobile)</span>
                                <span class="text-secondary small d-block">Jakarta, Indonesia | IP: 192.168.1.11 | Terakhir: 1 jam lalu</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <button class="btn btn-outline-danger w-100" style="min-height: 44px;" onclick="alert('Berhasil memutuskan seluruh sesi perangkat lain (Simulasi).')">
                                <i class="bi bi-box-arrow-right me-1.5"></i>Keluarkan Sesi Perangkat Lain
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SHEET 9: Privacy settings -->
            <div class="settings-tab-sheet" id="sheet-privacy" style="display: none;">
                <div class="card p-4 border-0">
                    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-eye-slash-fill text-primary me-2"></i>Kebijakan Privasi Data</h5>
                    
                    <x-profile-form id="form-privacy">
                        <x-toggle-switch id="data_sharing" label="Berbagi Data Satelit dengan Rekanan Logistik" :checked="true" />
                        <x-toggle-switch id="public_profile" label="Tampilkan Profil ke Publik (Eksportir Luar)" :checked="false" />
                        <x-toggle-switch id="analytics_consent" label="Izinkan Pengumpulan Log Kinerja Dasbor" :checked="true" />

                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary px-4" style="min-height: 44px;">Simpan Privasi</button>
                        </div>
                    </x-profile-form>
                </div>
            </div>

        </div>
    </div>

    <!-- Right Column: Summary Cards Info -->
    <div class="col-12 col-md-12 col-lg-3">
        <div class="d-flex flex-column gap-4">
            <!-- Summary Profile Card component -->
            <x-profile-card title="Informasi Keanggotaan">
                <div class="d-flex flex-column gap-3" style="font-size: 0.825rem;">
                    <div class="d-flex justify-content-between border-bottom pb-2">
                        <span class="text-secondary">Jabatan / Role:</span>
                        <span class="text-dark fw-bold">Admin Utama</span>
                    </div>
                    <div class="d-flex justify-content-between border-bottom pb-2">
                        <span class="text-secondary">Penyimpanan Terpakai:</span>
                        <span class="text-dark fw-bold">342 MB / 1 GB</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-secondary">Durasi Anggota:</span>
                        <span class="text-dark fw-semibold">1 Hari Bergabung</span>
                    </div>
                </div>
            </x-profile-card>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Run simulated loader
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
        }, 800);
    });

    // Single-Page Settings tab navigation switcher
    function switchSettingsTab(tabId, buttonElement) {
        // Hide all sheets
        const sheets = document.querySelectorAll('.settings-tab-sheet');
        sheets.forEach(sh => sh.style.display = 'none');

        // Show matched tab sheet
        const target = document.getElementById(`sheet-${tabId}`);
        if (target) target.style.display = 'block';

        // Remove active class from other buttons
        const menuButtons = document.querySelectorAll('.settings-menu-item');
        menuButtons.forEach(btn => btn.classList.remove('active'));

        // Add active class to clicked button
        // Supports both desktop sidebar and mobile accordion links
        const matches = Array.from(document.querySelectorAll('.settings-menu-item')).filter(btn => {
            return btn.textContent.trim().toLowerCase().includes(buttonElement.textContent.trim().toLowerCase());
        });
        matches.forEach(m => m.classList.add('active'));
    }

    // Collapse mobile accordion after click choice
    function collapseAccordion() {
        const collapseEl = document.getElementById('collapseSettingsMenu');
        const bsCollapse = bootstrap.Collapse.getInstance(collapseEl);
        if (bsCollapse) bsCollapse.hide();
    }

    // Submit forms simulations trigger success validation message
    const forms = ['form-edit-profile', 'form-security', 'form-notification', 'form-language', 'form-regional', 'form-privacy'];
    forms.forEach(formId => {
        const form = document.getElementById(formId);
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const feedback = document.getElementById('settings-feedback-area');
                feedback.style.display = 'block';
                
                // auto scroll top to see success alerts
                window.scrollTo({ top: 0, behavior: 'smooth' });

                setTimeout(() => {
                    feedback.style.display = 'none';
                }, 2000);
            });
        }
    });

    // Previews Theme Modes selector
    function switchThemeMode(theme, element) {
        const cards = document.querySelectorAll('.theme-selector-card');
        cards.forEach(c => c.classList.remove('active'));
        
        element.classList.add('active');
        alert(`Tema ${theme} dipilih (Pratinjau). Pengaturan visual akan disimpan secara permanen pada database.`);
    }

    // Error recovery simulator
    function simulateErrorState() {
        document.getElementById('main-content-grid').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'none';
        document.getElementById('error-state-container').style.display = 'flex';
    }

    function retryFromError() {
        document.getElementById('error-state-container').style.display = 'none';
        document.getElementById('skeleton-container').style.display = 'block';
        setTimeout(() => {
            document.getElementById('skeleton-container').style.display = 'none';
            document.getElementById('main-content-grid').style.display = 'flex';
        }, 800);
    }
</script>

<style>
    /* Theme selector cards custom borders */
    .theme-selector-card {
        border-color: var(--border-color) !important;
        background-color: #FFFFFF;
        transition: all 0.2s;
    }
    .theme-selector-card:hover {
        border-color: var(--primary) !important;
    }
    .theme-selector-card.active {
        border-color: var(--primary) !important;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.12) !important;
    }
</style>
@endsection
