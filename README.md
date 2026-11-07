# 🚢 Vessel Risk Management (VRM) Platform

Platform inspeksi risiko kapal, manajemen survei maritim, dan underwriting asuransi berbasis **Laravel 12 + Filament v3 + PostgreSQL 18**.

Modernisasi dari platform legacy CodeIgniter 3 HMVC dengan mempertahankan 100% kesesuaian alur kerja bisnis.

---

## 🛠️ Spesifikasi Teknologi
* **Framework**: Laravel 12.x
* **Admin / UI**: Filament v3 (Tailwind CSS, Livewire 3, Alpine.js)
* **PHP Runtime**: PHP 8.2+
* **Database**: PostgreSQL 18+ (dengan dukungan JSONB untuk checklist survei)
* **Tema Visual**: Maritime Deep Navy (`#27235f`) & Font Manrope

---

## 📂 Modul Inti & Alur Kerja Bisnis
1. **Master Kapal (`Vessels`)**:
   * Registrasi kapal, nomor IMO, Gross Tonnage (GRT), DWT, bendera negara, klasifikasi (BKI / IACS), dan histori drydock.
2. **Survei Kondisi (`Surveys`)**:
   * Penugasan inspektur maritim (Surveyor).
   * Pengisian checklist inspeksi fleksibel bertipe JSONB (Hull, Machinery, Safety, Statutory Certificates).
   * Unggahan bukti dokumentasi & foto inspeksi.
   * Kalkulasi instan *Risk Rating Index* (Low, Moderate, High, Critical).
3. **Defisiensi & Rekomendasi (`SurveyDefects`)**:
   * Pencatatan kerusakan temuan surveyor di lapangan.
   * Alur perbaikan oleh pemilik kapal (*Rectification Owner*).
4. **Mitra Bisnis**:
   * Perusahaan Asuransi (*Underwriters*), Perusahaan Pemilik/Operator Kapal (*Shipowners*), Broker, dan Marine Surveyor.

---

## 🚀 Panduan Setup Lokal

### 1. Prasyarat
* PHP >= 8.2 dengan ekstensi: `pdo_pgsql`, `pgsql`, `intl`, `mbstring`, `fileinfo`, `zip`
* PostgreSQL 18+ berjalan di port 5432
* Composer

### 2. Konfigurasi Database
Buat database di PostgreSQL:
```sql
CREATE DATABASE vrm_modern;
```

Salin file konfigurasi:
```bash
cp .env.example .env
php artisan key:generate
```
Pastikan pengaturan di `.env`:
```ini
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=vrm_modern
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

### 3. Migrasi & Seeding
Jalankan migrasi skema tabel dan data master tipe kapal:
```bash
php artisan migrate --seed
```

### 4. Menjalankan Server Lokal
```bash
php artisan serve
```
Akses panel admin di browser:
👉 **`http://127.0.0.1:8000/admin`**

**Akun Administrator Awal:**
* **Email**: `admin@vesselriskmanagement.com`
* **Password**: `password123`
