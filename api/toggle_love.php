<?php
// User gives "love"/checklist to an ad -> recorded, then ad is replaced by another.
require_once __DIR__ . '/../includes/functions.php';
csrf_check();
$u = require_login();

$adId   = (int)field('ad_id');
$exclude = body()['exclude'] ?? [];   // ids currently shown on the grid
if (!is_array($exclude)) $exclude = [];
$exclude = array_map('intval', $exclude);

$st = db()->prepare('SELECT id FROM ads WHERE id = ? LIMIT 1');
$st->execute([$adId]);
if (!$st->fetch()) json_err('Iklan tidak ditemukan.');

// record love (once)
$chk = db()->prepare('SELECT id FROM watch_history WHERE user_id=? AND ad_id=? AND type=? LIMIT 1');
$chk->execute([$u['id'], $adId, 'love']);
if (!$chk->fetch()) {
    db()->prepare('INSERT INTO watch_history (user_id,ad_id,type,stars_earned) VALUES (?,?,?,0)')
        ->execute([$u['id'], $adId, 'love']);
}

// loved ads to exclude from replacement
$lv = db()->prepare('SELECT DISTINCT ad_id FROM watch_history WHERE user_id=? AND type=?');
$lv->execute([$u['id'], 'love']);
$loved = array_column($lv->fetchAll(), 'ad_id');

$skip = array_unique(array_merge($exclude, $loved, [$adId]));
$replacement = null;
$sql = 'SELECT id, ad_number, title, video_url, duration, star_reward, is_active FROM ads';
if ($skip) {
    $in = implode(',', array_fill(0, count($skip), '?'));
    $sql .= " WHERE id NOT IN ($in)";
}
$sql .= ' ORDER BY RAND() LIMIT 1';
$rs = db()->prepare($sql);
$rs->execute(array_values($skip));
$rep = $rs->fetch();
if ($rep) {
    $replacement = [
        'id' => (int)$rep['id'], 'ad_number' => (int)$rep['ad_number'],
        'title' => $rep['title'], 'video_url' => $rep['video_url'],
        'duration' => (int)$rep['duration'], 'star_reward' => (int)$rep['star_reward'],
        'is_active' => (int)$rep['is_active'],
    ];
}
json_ok(['replacement' => $replacement], 'Ditambahkan ke favorit ❤');
