<?php
require_once __DIR__ . '/../includes/functions.php';
$u = current_user();
if (!$u) { header('Location: ../login.php'); exit; }
if ($u['role'] !== 'admin') { header('Location: ../dashboard.php'); exit; }
$BASE = '../'; $PAGE_TITLE = 'Kelola Iklan'; $ACTIVE = 'ads';
include __DIR__ . '/../partials/header.php';
?>
<div class="container" style="padding-top:28px;padding-bottom:60px">
  <div class="grid-head">
    <h2 style="margin:0">Daftar <span class="gradient-text">Tayangan</span></h2>
    <div style="display:flex;gap:10px;align-items:flex-end;flex-wrap:wrap">
      <div class="field" style="margin:0">
        <label>Default STAR semua iklan</label>
        <div style="display:flex;gap:8px">
          <input class="input" id="defStar" type="number" min="0" style="width:100px" value="30">
          <button class="btn sm" id="applyAll">Terapkan ke Semua</button>
        </div>
      </div>
    </div>
  </div>
  <div class="panel"><div id="adsWrap"><div class="spinner"></div></div></div>
</div>

<!-- edit ad modal -->
<div class="modal modal-sm" id="adModal">
  <div class="modal-card">
    <div class="player-top"><b>Edit Iklan</b><button class="x" id="adClose">✕</button></div>
    <div class="pad" style="padding:24px">
      <form id="adForm">
        <input type="hidden" name="id">
        <div class="field"><label>Judul</label><input class="input" name="title" required></div>
        <div class="field"><label>URL Video</label><input class="input" name="video_url"></div>
        <div class="grid-2">
          <div class="field"><label>Durasi (detik)</label><input class="input" type="number" name="duration" min="5" max="120"></div>
          <div class="field"><label>STAR Reward</label><input class="input" type="number" name="star_reward" min="0"></div>
        </div>
        <button class="btn block" type="submit">Simpan</button>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="../assets/js/admin.js" defer></script>
<script>document.addEventListener('DOMContentLoaded',()=>AdminAds());</script>
