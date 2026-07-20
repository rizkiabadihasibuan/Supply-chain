# 🚀 Panduan Hosting Railway & Cloud Deployment

Dokumen ini berisi langkah-langkah untuk melakukan deployment aplikasi **Global Supply Chain Risk Intelligence Platform** ke **Railway.app** (seperti URL contoh `https://global-supply-chain-production.up.railway.app`).

---

## 🛠️ Langkah-Langkah Deployment di Railway:

### Langkah 1: Push Project ke GitHub
1. Pastikan seluruh perubahan kode telah di-commit ke repository GitHub Anda:
   ```bash
   git add .
   git commit -m "Prepare for Railway deployment"
   git push origin main
   ```

### Langkah 2: Buat Project Baru di Railway
1. Buka [Railway.app](https://railway.app) dan login dengan akun GitHub Anda.
2. Klik tombol **"+ New Project"**.
3. Pilih **"Deploy from GitHub repo"** dan pilih repository `Supply-chain` Anda.

### Langkah 3: Tambahkan Database MySQL di Railway
1. Di dalam kanvas project Railway Anda, klik **"+ New"** $\rightarrow$ **"Database"** $\rightarrow$ **"Add MySQL"**.
2. Railway secara otomatis akan membuatkan database MySQL cloud dengan variabel koneksi (`MYSQLHOST`, `MYSQLPORT`, `MYSQLDATABASE`, `MYSQLUSER`, `MYSQLPASSWORD`).

### Langkah 4: Konfigurasi Environment Variables (ENV)
Di service aplikasi Laravel Anda di Railway, buka tab **Variables** dan tambahkan variabel berikut:

| Key | Value Contoh |
|---|---|
| `APP_NAME` | `Global Supply Chain` |
| `APP_ENV` | `production` |
| `APP_KEY` | *(Salin dari file .env lokal Anda)* |
| `APP_DEBUG` | `false` |
| `APP_URL` | `https://your-project.up.railway.app` |
| `DB_CONNECTION` | `mysql` |
| `DB_HOST` | `${{MySQL.MYSQLHOST}}` |
| `DB_PORT` | `${{MySQL.MYSQLPORT}}` |
| `DB_DATABASE` | `${{MySQL.MYSQLDATABASE}}` |
| `DB_USERNAME` | `${{MySQL.MYSQLUSER}}` |
| `DB_PASSWORD` | `${{MySQL.MYSQLPASSWORD}}` |
| `OPEN_METEO_URL` | `https://api.open-meteo.com/v1` |
| `WORLD_BANK_URL` | `https://api.worldbank.org/v2` |
| `REST_COUNTRIES_URL` | `https://restcountries.com/v3.1` |
| `EXCHANGE_RATE_URL` | `https://open.er-api.com/v6/latest` |

### Langkah 5: Run Migrations & Seeders di Railway
Buka tab **Settings** $\rightarrow$ **Deploy Command** atau jalankan via Railway CLI:
```bash
php artisan migrate:fresh --seed --force
```

---

## ✅ Berkas Deployment yang Telah Disediakan:
- [Procfile](file:///c:/xampp/htdocs/Supply-chain/Procfile): Konfigurasi proses web server Railway.
- [nixpacks.toml](file:///c:/xampp/htdocs/Supply-chain/nixpacks.toml): Buildpack PHP 8.3 & Composer otomatis.
- [Dockerfile](file:///c:/xampp/htdocs/Supply-chain/Dockerfile): Kontainerisasi Docker Apache/PHP 8.3 standar produksi.
