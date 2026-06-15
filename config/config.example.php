<?php
/**
 * AdvertiseMaster — Main Configuration
 * -------------------------------------------------------------
 * Isi kredensial di bawah ini dengan data dari Hostinger hPanel.
 * (Databases -> Management -> lihat host, nama database, user, password)
 * -------------------------------------------------------------
 */

// ===== DATABASE (Hostinger MySQL) =====
define('DB_HOST', 'localhost');        // Hostinger biasanya 'localhost'
define('DB_NAME', 'u000000000_adsmaster'); // ganti dgn nama database Anda
define('DB_USER', 'u000000000_admin');     // ganti dgn user database Anda
define('DB_PASS', 'GANTI_PASSWORD_DB');    // ganti dgn password database Anda
define('DB_CHARSET', 'utf8mb4');

// ===== ADMIN BAWAAN (dibuat otomatis saat install.php dijalankan) =====
define('DEFAULT_ADMIN_EMAIL', 'ericmulyono2@gmail.com');
define('DEFAULT_ADMIN_PASSWORD', '112233');
define('DEFAULT_ADMIN_NAME', 'Eric Mulyono');

// ===== SMTP (untuk kirim OTP email — daftar & lupa password) =====
// Gmail: SMTP_HOST=smtp.gmail.com, PORT=587, SECURE=tls, USER=email, PASS=App Password 16 digit
// Hostinger email: SMTP_HOST=smtp.hostinger.com, PORT=465, SECURE=ssl
define('SMTP_HOST', '');               // mis. 'smtp.gmail.com'
define('SMTP_PORT', 587);              // 587 (tls) atau 465 (ssl)
define('SMTP_SECURE', 'tls');          // 'tls' atau 'ssl'
define('SMTP_USER', '');               // alamat email pengirim
define('SMTP_PASS', '');               // password / app password
define('SMTP_FROM_NAME', 'AdvertiseMaster');

// ===== APP =====
define('APP_NAME', 'AdvertiseMaster');
define('DEFAULT_STAR_REWARD', 30);     // star default tiap tayangan selesai
define('GRID_SIZE', 50);               // jumlah grid iklan yang tampil
define('OTP_EXPIRY_MIN', 10);          // OTP berlaku berapa menit

// ===== TIMEZONE =====
date_default_timezone_set('Asia/Jakarta');

// ===== ERROR (set false di production) =====
define('APP_DEBUG', true);
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}
