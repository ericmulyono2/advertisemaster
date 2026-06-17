<?php
require_once __DIR__ . '/../includes/functions.php';
$BASE      = $BASE      ?? '';
$PAGE_TITLE= $PAGE_TITLE?? APP_NAME;
$ACTIVE    = $ACTIVE    ?? '';
$HIDE_NAV  = $HIDE_NAV  ?? false;
$cu        = current_user();
function nav_avatar($u, $base) {
    if (!empty($u['avatar'])) return '<img class="avatar" src="' . e($base . $u['avatar']) . '" alt="">';
    $initial = strtoupper(mb_substr($u['name'] ?? 'U', 0, 1));
    return '<span class="avatar">' . e($initial) . '</span>';
}
?>
<!doctype html>
<html lang="id" data-theme="light">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title><?= e($PAGE_TITLE) ?> — <?= e(APP_NAME) ?></title>
<meta name="csrf" content="<?= e(csrf_token()) ?>">
<meta name="base" content="<?= e($BASE) ?>">
<link rel="icon" href="<?= e($BASE) ?>assets/img/logo.svg">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap">
<link rel="stylesheet" href="<?= e($BASE) ?>assets/css/style.css">
<script>(function(){var t=localStorage.getItem('am-theme')||'light';document.documentElement.setAttribute('data-theme',t);})();</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js" defer></script>
</head>
<body>
<div class="bg-fx"><div class="blob b1"></div><div class="blob b2"></div><div class="blob b3"></div></div>
<div class="bg-grid"></div>

<?php if (!$HIDE_NAV && $cu): ?>
<?php $isAdmin = is_panel_role($cu['role']); ?>
<nav class="nav"><div class="container nav-in">
  <a class="brand" href="<?= e($BASE) ?><?= $isAdmin ? 'admin/index.php' : 'dashboard.php' ?>">
    <?php readfile(__DIR__ . '/../assets/img/logo.svg'); ?>
  </a>
  <div class="nav-links">
    <?php if ($isAdmin): ?>
      <a href="<?= e($BASE) ?>admin/index.php"   data-i18n="nav_dashboard" class="<?= $ACTIVE==='dashboard'?'active':'' ?>">Dashboard</a>
      <a href="<?= e($BASE) ?>admin/users.php"   data-i18n="nav_users" class="<?= $ACTIVE==='users'?'active':'' ?>">Pengguna</a>
      <a href="<?= e($BASE) ?>admin/ads.php"     data-i18n="nav_ads" class="<?= $ACTIVE==='ads'?'active':'' ?>">Iklan</a>
      <a href="<?= e($BASE) ?>admin/profile.php" data-i18n="nav_profile" class="<?= $ACTIVE==='profile'?'active':'' ?>">Profil</a>
    <?php else: ?>
      <a href="<?= e($BASE) ?>dashboard.php" data-i18n="nav_home" class="<?= $ACTIVE==='home'?'active':'' ?>">Beranda</a>
      <a href="<?= e($BASE) ?>stats.php"     data-i18n="nav_stats" class="<?= $ACTIVE==='stats'?'active':'' ?>">Statistik</a>
      <a href="<?= e($BASE) ?>profile.php"   data-i18n="nav_profile" class="<?= $ACTIVE==='profile'?'active':'' ?>">Profil</a>
    <?php endif; ?>
  </div>
  <div class="nav-right">
    <?php if (!$isAdmin): ?>
    <span class="star-chip"><?= AM_star() ?><span id="navStars"><?= (int)$cu['stars'] ?></span></span>
    <?php endif; ?>
    <?php include __DIR__ . '/lang_switch.php'; ?>
    <button class="theme-toggle" data-theme-toggle title="Ganti tema">
      <span class="ic moon">🌙</span><span class="ic sun">☀️</span><span class="knob"></span>
    </button>
    <a href="<?= e($BASE) ?>profile.php" title="<?= e($cu['name']) ?>"><?= nav_avatar($cu,$BASE) ?></a>
    <a href="<?= e($BASE) ?>api/logout.php" class="btn ghost sm" title="Logout" style="padding:8px 11px">⏻</a>
  </div>
</div></nav>
<?php endif; ?>
<?php
function AM_star(){return '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.7 7-6.3-3.8L5.7 21l1.7-7L2 9.2l7.1-.6z"/></svg>';}
?>
