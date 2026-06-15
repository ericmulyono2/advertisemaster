<?php
require_once __DIR__ . '/../../includes/functions.php';
require_admin();

$pdo = db();
$totalUsers = (int)$pdo->query('SELECT COUNT(*) c FROM users WHERE role="user"')->fetch()['c'];
$totalAds   = (int)$pdo->query('SELECT COUNT(*) c FROM ads')->fetch()['c'];
$activeAds  = (int)$pdo->query('SELECT COUNT(*) c FROM ads WHERE is_active=1')->fetch()['c'];
$totalWatch = (int)$pdo->query('SELECT COUNT(*) c FROM watch_history WHERE type="watch"')->fetch()['c'];
$totalLove  = (int)$pdo->query('SELECT COUNT(*) c FROM watch_history WHERE type="love"')->fetch()['c'];
$totalStars = (int)$pdo->query('SELECT COALESCE(SUM(stars),0) s FROM users')->fetch()['s'];

// last 7 days watch count
$daily = $pdo->query('SELECT DATE(created_at) d, COUNT(*) c FROM watch_history
    WHERE type="watch" AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at) ORDER BY d')->fetchAll();

// recent signups
$recent = $pdo->query('SELECT id,name,email,created_at FROM users WHERE role="user" ORDER BY id DESC LIMIT 5')->fetchAll();

json_ok([
    'stats' => compact('totalUsers','totalAds','activeAds','totalWatch','totalLove','totalStars'),
    'daily' => $daily,
    'recent' => $recent,
]);
