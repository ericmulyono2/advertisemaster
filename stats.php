<?php
require_once __DIR__ . '/includes/functions.php';
$u = current_user();
if (!$u) { header('Location: login.php'); exit; }
$BASE = ''; $PAGE_TITLE = 'Statistik'; $ACTIVE = 'stats';
include __DIR__ . '/partials/header.php';
?>
<div class="container" style="padding-top:28px;padding-bottom:60px">
  <h2 style="margin:0 0 18px">Statistik <span class="gradient-text">Saya</span></h2>
  <div class="stat-cards">
    <div class="stat-card"><div class="ic">⭐</div><div class="v" id="sStar">0</div><div class="l">Total STAR</div></div>
    <div class="stat-card"><div class="ic">▶️</div><div class="v" id="sWatch">0</div><div class="l">Tayangan Selesai</div></div>
    <div class="stat-card"><div class="ic">❤️</div><div class="v" id="sLove">0</div><div class="l">Diberi Love</div></div>
  </div>

  <div class="panel">
    <div class="tabs">
      <button data-f="all" class="active">Semua</button>
      <button data-f="watch">Ditonton</button>
      <button data-f="love">Favorit ❤</button>
    </div>
    <div id="histWrap"><div class="spinner"></div></div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/stats.js" defer></script>
