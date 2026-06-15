# 🚀 AdvertiseMaster

Platform menonton iklan modern & futuristik. Pengguna menonton iklan dalam grid, mengumpulkan **STAR ⭐**, dan menandai favorit ❤. Admin mengatur iklan, pengguna, dan reward.

Dibangun dengan **PHP + MySQL** (siap deploy di **Hostinger**), dengan UI futuristik, animasi **GSAP**, dan **dark/light mode**.

---

## ✨ Fitur

### Pengguna
- 🔐 **Login / Daftar** dengan verifikasi **OTP via email**
- 🔑 **Lupa password** → kode OTP ke email → reset password
- 🎬 **Grid 50 iklan** (diacak dari pool 100 iklan, durasi 10–30 detik)
- ❤️ **Love / checklist** → iklan langsung diganti iklan lain
- ⭐ **STAR points** — dapat star tiap menonton iklan **sampai selesai**
- 📊 **Statistik** — riwayat tontonan & favorit per hari/tanggal/bulan
- 👤 **Profil** — ganti foto, nama, lihat ID, ganti password, logout

### Admin
- 🔐 Login admin: `ericmulyono2@gmail.com` / `112233`
- 📈 **Dashboard** statistik pengguna & aktivitas
- 👥 **Database pengguna** (otomatis tersimpan saat user daftar)
- 🔍 Lihat riwayat tontonan tiap user + **hapus** entri
- 🔑 **Ganti password** user mana pun
- 📺 **Kelola 100 iklan**: tombol **ON/OFF** per iklan
  - ON = user bisa klik & menonton (tampil terang)
  - OFF = user hanya bisa lihat (abu-abu, tidak bisa diklik)
- ⭐ **Atur STAR** per iklan (default 30) atau terapkan ke semua
- 👤 Profil admin (foto, nama, ganti password, logout)

---

## 📦 Cara Deploy di Hostinger

### 1. Buat Database MySQL
hPanel → **Databases → MySQL Databases** → buat database baru.
Catat: **Database name**, **Username**, **Password**, **Host** (biasanya `localhost`).

### 2. Upload File
- hPanel → **File Manager** → masuk ke folder `public_html`
- Upload semua isi folder ini (atau hasil `git clone`) ke `public_html`

### 3. Isi Konfigurasi
Edit file **`config/config.php`**, isi bagian database:
```php
define('DB_NAME', 'u123456789_adsmaster');
define('DB_USER', 'u123456789_admin');
define('DB_PASS', 'password_database_anda');
```
(Opsional) Isi **SMTP** agar OTP email terkirim — lihat bagian Email di bawah.

### 4. Jalankan Installer
Buka di browser: **`https://domainanda.com/install.php`**
Installer akan membuat semua tabel, akun admin, dan **100 iklan** otomatis.

> ⚠️ **Setelah selesai, HAPUS / rename `install.php`** demi keamanan.

### 5. Selesai 🎉
Buka `https://domainanda.com/` → login.
- **Admin**: `ericmulyono2@gmail.com` / `112233`
- **User**: daftar lewat halaman Daftar.

---

## 📧 Mengaktifkan OTP Email

OTP untuk **daftar** & **lupa password** butuh konfigurasi SMTP di `config/config.php`.

**Gmail** (rekomendasi):
1. Aktifkan 2-Step Verification di akun Google
2. Buat **App Password** (16 digit) di https://myaccount.google.com/apppasswords
3. Isi:
```php
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USER', 'emailanda@gmail.com');
define('SMTP_PASS', 'xxxxxxxxxxxxxxxx'); // app password
```

**Email Hostinger**:
```php
define('SMTP_HOST', 'smtp.hostinger.com');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl');
define('SMTP_USER', 'admin@domainanda.com');
define('SMTP_PASS', 'password_email');
```

> Saat `APP_DEBUG = true` dan SMTP belum diisi, kode OTP ditampilkan langsung di layar untuk testing.

---

## 🎥 Mengganti Video Iklan
Default memakai URL video sample. Untuk mengganti dengan iklan asli:
**Admin → Iklan → Edit** → ubah **URL Video** (MP4) tiap iklan.

---

## 🗂️ Struktur Proyek
```
advertisemaster/
├── config/config.php        # kredensial DB & SMTP
├── includes/                # db, auth, helpers, mailer SMTP
├── api/                     # endpoint pengguna (login, ads, stars, dll)
│   └── admin/               # endpoint admin
├── assets/{css,js,img,uploads}
├── partials/                # header, footer, body bersama
├── admin/                   # halaman admin
├── *.php                    # halaman pengguna (login, dashboard, dll)
├── install.php              # installer sekali jalan
└── database/schema.sql      # referensi skema
```

## 🛠️ Teknologi
PHP 8 · MySQL/MariaDB · Vanilla JS · GSAP 3 · CSS custom (no framework) · SMTP mailer mandiri (tanpa Composer).

## 🔒 Keamanan
Password di-hash (`password_hash`), proteksi CSRF, prepared statements (PDO), validasi upload, OTP kadaluarsa, dan proteksi eksekusi PHP di folder uploads.

---
© AdvertiseMaster
