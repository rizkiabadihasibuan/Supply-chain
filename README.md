# 🌐 Global Supply Chain Monitoring & Risk Intelligence Platform

![Laravel](https://img.shields.io/badge/Laravel-13.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-blue.style=for-the-badge)

Platform intelijen dan analisis risiko rantai pasok global berbasis web real-time yang dirancang untuk memantau indikator makroekonomi, cuaca ekstrem, aktivitas pelabuhan maritim, sentimen berita, dan stabilitas mata uang dari negara-negara di seluruh dunia.

---

## 🚀 Fitur Utama

- 🌍 **Global Country Intelligence**:
  - Pemetaan geolokasi interaktif berbasis **Leaflet.js** dengan skor risiko terintegrasi.
  - Profil rinci indikator makroekonomi (GDP, Inflasi, Populasi, Mata Uang, & Kode ISO).
- ⚡ **Pemantauan Cuaca Ekstrem (Weather Monitoring)**:
  - Pemantauan kondisi atmosfer real-time (Suhu, Presipitasi/Hujan, Kecepatan Angin).
  - Peringatan dini badai siklon & cuaca buruk maritim.
- 🛡️ **Sistem Evaluasi & Skor Risiko (Risk Engine)**:
  - Pembobotan sub-indikator otomatis (Ekonomi, Politik, Cuaca, dan Logistik Pelabuhan).
  - Klasifikasi level risiko: *Very Low*, *Low*, *Medium*, *High*, *Critical*.
- ⚓ **Intelijen Pelabuhan Maritim (World Port Index)**:
  - Integrasi stasiun pelabuhan utama dunia.
  - Analisis dampak rute logistik dan potensi penundaan kapal (*vessel delay*).
- 📰 **Sentimen Berita & Analisis AI (News & AI Sentiment)**:
  - Aggregator berita rantai pasok real-time dari berbagai sumber terpercaya.
  - Analisis sentimen otomatis kata kunci negatif untuk deteksi risiko dini.
- 💱 **Intelijen Keuangan & Valuta (Currency Exchange)**:
  - Pelacakan nilai tukar mata uang global (USD base rate) dan analisis tren harian.
- 📊 **Alat Komparasi Antar Negara (Comparison Tool)**:
  - Perbandingan *head-to-head* indikator risiko, GDP, inflasi, dan valuta antara dua negara.
- 📑 **Ekspor Laporan PDF (Automated Reporting)**:
  - Generasi otomatis dokumen laporan intelijen eksekutif siap cetak/unduh.
- 👥 **Manajemen Akses Multi-Peran (Role-Based Access Control)**:
  - Dashboard khusus **User** (Analisis & Monitoring) dan **Admin** (Manajemen Data & Dataset Artikel/Pelabuhan).

---

## 🛠️ Stack Teknologi

| Komponen | Teknologi |
| :--- | :--- |
| **Backend Framework** | [Laravel 13.x](https://laravel.com/) (PHP 8.3+) |
| **Frontend UI** | Blade Templates, Vanilla CSS Custom Design System, Bootstrap 5.3, Bootstrap Icons |
| **Visualisasi Peta** | [Leaflet.js](https://leafletjs.com/) & OpenStreetMap |
| **Database** | MySQL / MariaDB / PostgreSQL / SQLite |
| **Arsitektur API** | RESTful API v1 dengan Resource Wrappers & Middleware Throttle |

---

## 📋 Persyaratan Sistem

Sebelum menjalankan proyek ini di lingkungan lokal Anda, pastikan telah menginstal:

- **PHP** `>= 8.3` (dengan ekstensi `pdo`, `mbstring`, `openssl`, `curl`, `json`, `fileinfo`)
- **Composer** `>= 2.5`
- **Node.js** `>= 18.0` & **NPM**
- **Web Server** (XAMPP / Laragon / Apache / Nginx)
- **Database Server** (MySQL / MariaDB)

---

## 🔧 Panduan Instalasi & Pengaturan Lokal

### 1. Clone Repositori
```bash
git clone https://github.com/username/Supply-chain.git
cd Supply-chain
```

### 2. Instalasi Dependensi Backend & Frontend
```bash
composer install
npm install
```

### 3. Konfigurasi Environment
Salin berkas `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka berkas `.env` lalu sesuaikan konfigurasi database Anda:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=supply_chain
DB_USERNAME=root
DB_PASSWORD=
```

### 4. Generate Key & Migrasi Database
```bash
php artisan key:generate
php artisan migrate --seed
```

### 5. Build Aset Frontend
```bash
npm run build
```

### 6. Jalankan Server Lokal
Anda dapat menjalankan server menggunakan script terpadu Composer:
```bash
composer dev
```
Atau jalankan server Artisan secara manual:
```bash
php artisan serve
```
Akses aplikasi melalui peramban web di: `http://127.0.0.1:8000`

---

## 🔌 Dokumentasi REST API (v1)

Aplikasi ini dilengkapi dengan API v1 terproteksi untuk integrasi sistem external:

| Method | Endpoint | Deskripsi |
| :--- | :--- | :--- |
| `POST` | `/api/v1/auth/login` | Otentikasi pengguna & pembuatan sesi |
| `GET` | `/api/v1/dashboard` | Ringkasan data KPI & analisis global |
| `GET` | `/api/v1/countries` | Daftar seluruh negara & skor risiko |
| `GET` | `/api/v1/countries/{id}/intelligence` | Intelijen lengkap satu negara |
| `GET` | `/api/v1/weather` | Data cuaca real-time seluruh negara |
| `GET` | `/api/v1/ports` | Daftar stasiun pelabuhan maritim |
| `GET` | `/api/v1/risk` | Evaluasi & riwayat tren risiko |
| `GET` | `/api/v1/currencies` | Kurs mata uang & tren harian |
| `GET` | `/api/v1/news` | Stream berita rantai pasok global |
| `GET` | `/api/v1/comparison?country_a={a}&country_b={b}` | Hasil komparasi dua negara |
| `POST` | `/api/v1/watchlists` | Menambahkan negara ke daftar favorit |

---

## 📁 Struktur Direktori Utama

```
Supply-chain/
├── app/
│   ├── Console/Commands/    # Command kustom Artisan (SyncPorts, dll)
│   ├── Http/Controllers/    # Controller Web, API, Auth, & Admin
│   ├── Jobs/                # Background Job Queue (GenerateCountrySnapshot, dll)
│   ├── Models/              # Model Eloquent (Country, Weather, Port, Risk, dll)
│   ├── Repositories/        # Implementasi Pattern Repository
│   └── Services/            # Business Logic Services
├── database/
│   ├── factories/           # Factory data pengujian
│   ├── migrations/          # Schema migrasi database
│   └── seeders/             # Seeder data awal (Countries, Ports, Risks, dll)
├── public/                  # Asset publik terkompilasi
├── resources/
│   ├── css/                 # Stylesheet & Design System Token
│   ├── js/                  # Script frontend & integrasi API
│   └── views/               # Template Blade (Dashboard, Weather, Risk, Admin, dll)
├── routes/
│   ├── web.php              # Rute utama aplikasi web
│   ├── api.php              # Rute endpoint REST API
│   ├── admin.php            # Rute khusus panel Administrator
│   └── user.php             # Rute khusus modul Pengguna
└── tests/                   # Unit & Feature Automated Tests (PHPUnit/Pest)
```

---

## 🧪 Pengujian Otomatis

Untuk menjalankan suite pengujian otomatis PHPUnit:
```bash
php artisan test
```

---

## 📄 Lisensi

Proyek ini dirilis di bawah lisensi [MIT License](LICENSE).
