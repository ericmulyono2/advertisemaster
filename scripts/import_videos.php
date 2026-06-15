<?php
/**
 * Downloads the 100 videos from videos_manifest.json into assets/videos/{n}.mp4
 * and updates the ads table (video_url + title). Run on the server via CLI:
 *   php scripts/import_videos.php
 */
require_once __DIR__ . '/../includes/db.php';

$manifest = json_decode(@file_get_contents(__DIR__ . '/videos_manifest.json'), true);
if (!is_array($manifest)) { fwrite(STDERR, "manifest missing\n"); exit(1); }

$dir = __DIR__ . '/../assets/videos';
if (!is_dir($dir)) mkdir($dir, 0755, true);

$pdo = db();
$ok = 0; $fail = 0;
foreach ($manifest as $i => $m) {
    $n = $i + 1;
    if ($n > 100) break;
    $dest = "$dir/$n.mp4";
    if (is_file($dest) && filesize($dest) > 10000) {
        $pdo->prepare('UPDATE ads SET video_url=?, title=? WHERE ad_number=?')
            ->execute(["/assets/videos/$n.mp4", mb_substr($m['title'], 0, 180), $n]);
        echo "SKIP $n (exists)\n"; $ok++; continue;
    }
    $fp = fopen($dest, 'w');
    $ch = curl_init($m['url']);
    curl_setopt_array($ch, [
        CURLOPT_FILE => $fp, CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_TIMEOUT => 180, CURLOPT_CONNECTTIMEOUT => 30,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (AdvertiseMaster importer)',
    ]);
    curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch); fclose($fp);

    clearstatcache();
    if ($code == 200 && is_file($dest) && filesize($dest) > 10000) {
        $pdo->prepare('UPDATE ads SET video_url=?, title=? WHERE ad_number=?')
            ->execute(["/assets/videos/$n.mp4", mb_substr($m['title'], 0, 180), $n]);
        $ok++;
        echo "OK $n ($code) " . round(filesize($dest) / 1024) . "KB — " . $m['title'] . "\n";
    } else {
        @unlink($dest); $fail++;
        echo "FAIL $n ($code) — " . $m['url'] . "\n";
    }
    flush();
}
echo "DONE ok=$ok fail=$fail\n";
