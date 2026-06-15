<?php
// Admin deletes a watch/love history entry of a user.
require_once __DIR__ . '/../../includes/functions.php';
csrf_check();
require_admin();

$id = (int)field('id');
$st = db()->prepare('SELECT * FROM watch_history WHERE id = ?');
$st->execute([$id]);
$row = $st->fetch();
if (!$row) json_err('Data tidak ditemukan.');

db()->beginTransaction();
try {
    // if it was a watch with stars, deduct from user balance
    if ($row['type'] === 'watch' && (int)$row['stars_earned'] > 0) {
        db()->prepare('UPDATE users SET stars = GREATEST(0, stars - ?) WHERE id = ?')
            ->execute([(int)$row['stars_earned'], (int)$row['user_id']]);
    }
    db()->prepare('DELETE FROM watch_history WHERE id = ?')->execute([$id]);
    db()->commit();
} catch (Throwable $e) {
    db()->rollBack();
    json_err('Gagal menghapus.');
}
json_ok([], 'Riwayat dihapus.');
