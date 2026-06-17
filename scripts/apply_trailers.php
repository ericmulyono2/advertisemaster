<?php
/**
 * Points all ads to embedded YouTube movie trailers from trailers_manifest.json.
 * video_url is stored as "yt:VIDEOID"; the player embeds it via the IFrame API.
 * Run on the server:  php scripts/apply_trailers.php
 */
require_once __DIR__ . '/../includes/db.php';

$list = json_decode(@file_get_contents(__DIR__ . '/trailers_manifest.json'), true);
if (!is_array($list) || !$list) { fwrite(STDERR, "trailers_manifest.json missing/empty\n"); exit(1); }

$pdo = db();
$n = (int)$pdo->query('SELECT COUNT(*) c FROM ads')->fetch()['c'];
$upd = $pdo->prepare('UPDATE ads SET video_url=?, title=?, duration=30 WHERE ad_number=?');
$done = 0;
for ($i = 1; $i <= $n; $i++) {
    $t = $list[($i - 1) % count($list)];
    $title = trim((string)($t['title'] ?? $t['movie'] ?? 'Trailer'));
    $upd->execute(['yt:' . $t['videoId'], mb_substr($title, 0, 180), $i]);
    $done++;
}
echo "Updated $done ads to YouTube trailers (" . count($list) . " unique).\n";
