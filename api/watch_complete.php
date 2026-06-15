<?php
// Award stars when a user finishes watching an active ad to completion.
require_once __DIR__ . '/../includes/functions.php';
csrf_check();
$u = require_login();

$adId = (int)field('ad_id');
$st = db()->prepare('SELECT * FROM ads WHERE id = ? LIMIT 1');
$st->execute([$adId]);
$ad = $st->fetch();

if (!$ad)               json_err('Iklan tidak ditemukan.');
if (!(int)$ad['is_active']) json_err('Iklan ini sedang nonaktif.');

$reward = (int)$ad['star_reward'];

db()->beginTransaction();
try {
    db()->prepare('INSERT INTO watch_history (user_id,ad_id,type,stars_earned) VALUES (?,?,?,?)')
        ->execute([$u['id'], $adId, 'watch', $reward]);
    db()->prepare('UPDATE users SET stars = stars + ? WHERE id = ?')
        ->execute([$reward, $u['id']]);
    db()->commit();
} catch (Throwable $e) {
    db()->rollBack();
    json_err('Gagal menyimpan. Coba lagi.');
}

$total = (int)db()->query('SELECT stars FROM users WHERE id = ' . (int)$u['id'])->fetch()['stars'];
json_ok(['earned' => $reward, 'stars' => $total], "Selesai! +$reward STAR");
