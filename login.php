<?php
require_once __DIR__ . '/includes/functions.php';
if (current_user()) { header('Location: index.php'); exit; }
$BASE = ''; $PAGE_TITLE = 'Login'; $HIDE_NAV = true;
include __DIR__ . '/partials/header.php';
?>
<div class="center-wrap">
  <div class="panel glass auth-card">
    <div class="auth-head">
      <div class="logo-big"><?php readfile(__DIR__ . '/assets/img/logo.svg'); ?></div>
      <h1>Selamat <span class="gradient-text">Datang</span></h1>
      <p>Masuk untuk mulai menonton & kumpulkan STAR ⭐</p>
    </div>
    <form id="loginForm" autocomplete="on">
      <div class="field">
        <label>Email</label>
        <input class="input" type="email" name="email" placeholder="email@contoh.com" required>
      </div>
      <div class="field">
        <div class="row-between">
          <label>Password</label>
          <a href="forgot.php" style="font-size:13px;color:var(--cyan);font-weight:700">Lupa password?</a>
        </div>
        <input class="input" type="password" name="password" placeholder="••••••••" required>
      </div>
      <button class="btn block" type="submit">Masuk →</button>
    </form>
    <div class="auth-foot">Belum punya akun? <a href="register.php">Daftar sekarang</a></div>
    <div style="margin-top:18px;text-align:center">
      <button class="theme-toggle" data-theme-toggle><span class="ic moon">🌙</span><span class="ic sun">☀️</span><span class="knob"></span></button>
    </div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/auth.js" defer></script>
