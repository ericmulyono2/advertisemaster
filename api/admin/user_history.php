<?php
// Admin views a specific user's watch/love history.
require_once __DIR__ . '/../../includes/functions.php';
require_admin();

$uid = (int)($_GET['user_id'] ?? 0);
$st = db()->prepare('SELECT id,name,email,stars,avatar FROM users WHERE id = ?');
$st->execute([$uid]);
$user = $st->fetch();
if (!$user) json_err('User tidak ditemukan.', 404);

$h = db()->prepare('SELECT h.id, h.type, h.stars_earned, h.created_at, a.ad_number, a.title
    FROM watch_history h JOIN ads a ON a.id = h.ad_id
    WHERE h.user_id = ? ORDER BY h.created_at DESC LIMIT 1000');
$h->execute([$uid]);
json_ok(['user' => $user, 'history' => $h->fetchAll()]);
