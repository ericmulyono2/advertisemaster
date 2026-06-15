<?php
// Returns up to GRID_SIZE ads for the logged-in user, excluding ones already "loved".
require_once __DIR__ . '/../includes/functions.php';
$u = require_login();

// loved ad ids for this user
$lv = db()->prepare('SELECT DISTINCT ad_id FROM watch_history WHERE user_id = ? AND type = ?');
$lv->execute([$u['id'], 'love']);
$loved = array_column($lv->fetchAll(), 'ad_id');

$sql = 'SELECT id, ad_number, title, video_url, duration, star_reward, is_active FROM ads';
if ($loved) {
    $in = implode(',', array_fill(0, count($loved), '?'));
    $sql .= " WHERE id NOT IN ($in)";
}
$sql .= ' ORDER BY RAND() LIMIT ' . (int)GRID_SIZE;
$st = db()->prepare($sql);
$st->execute($loved);
$ads = $st->fetchAll();

// normalize types
foreach ($ads as &$a) {
    $a['id']          = (int)$a['id'];
    $a['ad_number']   = (int)$a['ad_number'];
    $a['duration']    = (int)$a['duration'];
    $a['star_reward'] = (int)$a['star_reward'];
    $a['is_active']   = (int)$a['is_active'];
}
json_ok(['ads' => $ads, 'stars' => (int)$u['stars']]);
