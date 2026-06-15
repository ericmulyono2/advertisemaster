<?php
require_once __DIR__ . '/../includes/functions.php';
$u = current_user();
if (!$u) { header('Location: ../login.php'); exit; }
if (!is_panel_role($u['role'])) { header('Location: ../dashboard.php'); exit; }
$BASE = '../'; $PAGE_TITLE = 'Dashboard Admin'; $ACTIVE = 'dashboard';
include __DIR__ . '/../partials/header.php';
?>
<div class="container" style="padding-top:28px;padding-bottom:60px">
  <h2 style="margin:0 0 18px">Dashboard <span class="gradient-text">Admin</span></h2>
  <div class="stat-cards" id="statCards"><div class="spinner"></div></div>

  <div class="grid-2">
    <div class="panel">
      <h3 class="section-title">Tayangan 7 Hari Terakhir</h3>
      <div class="bars" id="bars"></div>
    </div>
    <div class="panel">
      <h3 class="section-title">Pendaftar Terbaru</h3>
      <div id="recent"><div class="spinner"></div></div>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="../assets/js/admin.js" defer></script>
<script>document.addEventListener('DOMContentLoaded',()=>AdminDashboard());</script>
