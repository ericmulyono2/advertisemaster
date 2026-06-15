<?php
/**
 * AdvertiseMaster — Installer
 * Jalankan sekali di browser: https://domainanda.com/install.php
 * Membuat semua tabel, akun admin, settings, dan 100 iklan.
 * HAPUS / rename file ini setelah selesai (keamanan).
 */
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

header('Content-Type: text/html; charset=utf-8');
$log = [];
function step(&$log, $msg, $ok = true) { $log[] = ($ok ? '✅ ' : '❌ ') . $msg; }

try {
    $pdo = db();

    // ---------- USERS ----------
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(120) NOT NULL,
        email VARCHAR(190) NOT NULL UNIQUE,
        password_hash VARCHAR(255) NOT NULL,
        role ENUM('user','staff','admin') NOT NULL DEFAULT 'user',
        avatar VARCHAR(255) DEFAULT NULL,
        stars INT NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    step($log, 'Tabel users siap');

    // ---------- ADS ----------
    $pdo->exec("CREATE TABLE IF NOT EXISTS ads (
        id INT AUTO_INCREMENT PRIMARY KEY,
        ad_number INT NOT NULL,
        title VARCHAR(190) NOT NULL,
        video_url VARCHAR(500) NOT NULL,
        duration INT NOT NULL DEFAULT 15,
        star_reward INT NOT NULL DEFAULT 30,
        is_active TINYINT(1) NOT NULL DEFAULT 1,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    step($log, 'Tabel ads siap');

    // ---------- WATCH HISTORY (watch + love) ----------
    $pdo->exec("CREATE TABLE IF NOT EXISTS watch_history (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        ad_id INT NOT NULL,
        type ENUM('watch','love') NOT NULL DEFAULT 'watch',
        stars_earned INT NOT NULL DEFAULT 0,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX(user_id), INDEX(ad_id), INDEX(type)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    step($log, 'Tabel watch_history siap');

    // ---------- OTP / pending ----------
    $pdo->exec("CREATE TABLE IF NOT EXISTS otps (
        id INT AUTO_INCREMENT PRIMARY KEY,
        email VARCHAR(190) NOT NULL,
        code VARCHAR(6) NOT NULL,
        purpose ENUM('register','reset') NOT NULL,
        payload TEXT DEFAULT NULL,
        expires_at DATETIME NOT NULL,
        created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        INDEX(email), INDEX(purpose)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    step($log, 'Tabel otps siap');

    // ---------- SETTINGS ----------
    $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
        k VARCHAR(100) PRIMARY KEY,
        v TEXT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    set_setting('default_star_reward', DEFAULT_STAR_REWARD);
    step($log, 'Tabel settings siap');

    // ---------- SEED ADMIN ----------
    $st = $pdo->prepare('SELECT id FROM users WHERE email = ?');
    $st->execute([DEFAULT_ADMIN_EMAIL]);
    if (!$st->fetch()) {
        $ins = $pdo->prepare('INSERT INTO users (name,email,password_hash,role,stars) VALUES (?,?,?,?,0)');
        $ins->execute([
            DEFAULT_ADMIN_NAME,
            DEFAULT_ADMIN_EMAIL,
            password_hash(DEFAULT_ADMIN_PASSWORD, PASSWORD_DEFAULT),
            'admin'
        ]);
        step($log, 'Akun admin dibuat: ' . DEFAULT_ADMIN_EMAIL);
    } else {
        step($log, 'Akun admin sudah ada (dilewati)');
    }

    // ---------- SEED 100 ADS ----------
    $count = (int)$pdo->query('SELECT COUNT(*) c FROM ads')->fetch()['c'];
    if ($count < 100) {
        $samples = [
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerBlazes.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerEscapes.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerFun.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerJoyrides.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ForBiggerMeltdowns.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/Sintel.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/SubaruOutbackOnStreetAndDirt.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/TearsOfSteel.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/VolkswagenGTIReview.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WeAreGoingOnBullrun.mp4',
            'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/WhatCarCanYouGetForAGrand.mp4',
        ];
        $themes = ['Smartphone Pro','Sepatu Olahraga','Kopi Premium','Mobil Listrik','Fashion Urban',
            'Gadget Pintar','Liburan Tropis','Makanan Sehat','Aplikasi Fintech','Parfum Mewah',
            'Headset Gaming','Skincare Glow','Motor Sport','Furnitur Modern','Jam Tangan Elegan',
            'Laptop Ultrabook','Minuman Energi','Kamera Mirrorless','Sepeda Listrik','Tas Designer'];

        $ins = $pdo->prepare('INSERT INTO ads (ad_number,title,video_url,duration,star_reward,is_active) VALUES (?,?,?,?,?,1)');
        for ($i = $count + 1; $i <= 100; $i++) {
            $theme    = $themes[($i - 1) % count($themes)];
            $title    = sprintf('Iklan #%02d — %s', $i, $theme);
            $video    = $samples[($i - 1) % count($samples)];
            $duration = random_int(10, 30);
            $ins->execute([$i, $title, $video, $duration, DEFAULT_STAR_REWARD]);
        }
        step($log, '100 iklan berhasil dibuat (durasi acak 10-30 detik)');
    } else {
        step($log, 'Iklan sudah ada (' . $count . ' baris, dilewati)');
    }

    $done = true;
} catch (Throwable $ex) {
    $done = false;
    step($log, 'ERROR: ' . $ex->getMessage(), false);
}
?>
<!doctype html>
<html lang="id"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Install — AdvertiseMaster</title>
<style>
body{font-family:Segoe UI,Arial,sans-serif;background:#0a0e1a;color:#e6ecff;display:flex;min-height:100vh;align-items:center;justify-content:center;margin:0}
.card{background:#111729;border:1px solid #2a3358;border-radius:18px;padding:32px;max-width:560px;width:90%}
h1{background:linear-gradient(90deg,#6a5cff,#22d3ee);-webkit-background-clip:text;background-clip:text;color:transparent;margin:0 0 4px}
.sub{color:#8b96b8;margin:0 0 20px;font-size:13px}
ul{list-style:none;padding:0;line-height:1.9;font-size:14px}
.ok{color:#34d399}.no{color:#f87171}
a.btn{display:inline-block;margin-top:18px;background:linear-gradient(90deg,#6a5cff,#22d3ee);color:#0a0e1a;font-weight:700;padding:12px 22px;border-radius:10px;text-decoration:none}
.warn{margin-top:18px;background:#2a1a1a;border:1px solid #5a2a2a;color:#fca5a5;padding:12px;border-radius:10px;font-size:13px}
</style></head><body>
<div class="card">
  <h1>AdvertiseMaster</h1>
  <p class="sub">Hasil instalasi database</p>
  <ul><?php foreach ($log as $l) echo '<li>' . e($l) . '</li>'; ?></ul>
  <?php if (!empty($done)): ?>
    <a class="btn" href="index.php">Buka Aplikasi →</a>
    <div class="warn">⚠️ PENTING: Hapus atau ganti nama file <b>install.php</b> sekarang demi keamanan.</div>
  <?php endif; ?>
</div></body></html>
