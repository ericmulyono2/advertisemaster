<?php
require_once __DIR__ . '/includes/functions.php';
$u = current_user();
if (!$u) { header('Location: login.php'); exit; }
if ($u['role'] === 'admin') { header('Location: admin/index.php'); exit; }
$BASE = ''; $PAGE_TITLE = 'Beranda'; $ACTIVE = 'home';
include __DIR__ . '/partials/header.php';
?>
<div class="container">
  <div class="grid-head">
    <div>
      <h2><span class="gradient-text" data-i18n="grid_title">Galeri Iklan</span></h2>
      <div class="sub" data-i18n="grid_sub">Tonton sampai selesai untuk dapat STAR ⭐ · tekan ❤ untuk favorit & ganti iklan</div>
    </div>
    <button class="btn ghost sm" id="reloadGrid">↻ <span data-i18n="reshuffle">Acak Ulang</span></button>
  </div>
  <div id="grid" class="ads-grid"><div class="spinner"></div></div>
</div>

<!-- Player Modal -->
<div class="modal" id="player">
  <div class="modal-card">
    <div class="player-top">
      <div><b id="pvTitle">Iklan</b> <span class="muted" id="pvNum"></span></div>
      <button class="x" id="pvClose">✕</button>
    </div>
    <video class="player-video" id="pvVideo" playsinline></video>
    <div class="player-bar"><div class="fill" id="pvFill"></div></div>
    <div class="player-foot">
      <div class="info">Tonton hingga <b id="pvDur">0</b> detik untuk klaim
        <span class="reward" style="display:inline">+<span id="pvReward">30</span> ⭐</span></div>
      <div class="count">Sisa: <span id="pvCount">0</span>s</div>
    </div>
  </div>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/grid.js" defer></script>
