<?php
/**
 * Points all 100 ads to the animated HTML creatives and sets titles from products.json.
 * Run on the server CLI:  php scripts/apply_creatives.php
 */
require_once __DIR__ . '/../includes/db.php';

$products = json_decode(@file_get_contents(__DIR__ . '/../assets/ads/products.json'), true);
if (!is_array($products) || !$products) { fwrite(STDERR, "products.json missing\n"); exit(1); }

$pdo = db();
$total = (int)$pdo->query('SELECT COUNT(*) FROM ads')->fetch()['c'] ?: 100;
$n = (int)$pdo->query('SELECT COUNT(*) c FROM ads')->fetch()['c'];
$upd = $pdo->prepare('UPDATE ads SET video_url=?, title=? WHERE ad_number=?');
$done = 0;
for ($i = 1; $i <= $n; $i++) {
    $p = $products[($i - 1) % count($products)];
    $title = $p['name'] . ' — ' . $p['tag'];
    $url   = "/assets/ads/creative.html?id=$i";
    $upd->execute([$url, mb_substr($title, 0, 180), $i]);
    $done++;
}
echo "Updated $done ads to animated creatives.\n";
