<?php
// User's own activity history (watch + love) grouped by date.
require_once __DIR__ . '/../includes/functions.php';
$u = require_login();

$filter = $_GET['type'] ?? 'all'; // all | watch | love
$sql = 'SELECT h.id, h.type, h.stars_earned, h.created_at,
               a.ad_number, a.title
        FROM watch_history h JOIN ads a ON a.id = h.ad_id
        WHERE h.user_id = ?';
$params = [$u['id']];
if (in_array($filter, ['watch','love'], true)) {
    $sql .= ' AND h.type = ?';
    $params[] = $filter;
}
$sql .= ' ORDER BY h.created_at DESC LIMIT 500';
$st = db()->prepare($sql);
$st->execute($params);
$rows = $st->fetchAll();

// summary
$sumW = db()->prepare('SELECT COUNT(*) c, COALESCE(SUM(stars_earned),0) s FROM watch_history WHERE user_id=? AND type="watch"');
$sumW->execute([$u['id']]);
$w = $sumW->fetch();
$sumL = db()->prepare('SELECT COUNT(*) c FROM watch_history WHERE user_id=? AND type="love"');
$sumL->execute([$u['id']]);
$l = $sumL->fetch();

json_ok([
    'history' => $rows,
    'summary' => [
        'watch_count' => (int)$w['c'],
        'star_total'  => (int)$u['stars'],
        'love_count'  => (int)$l['c'],
    ],
]);
