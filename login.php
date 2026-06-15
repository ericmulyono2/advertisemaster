<?php
require_once __DIR__ . '/includes/functions.php';
if (current_user()) { header('Location: index.php'); exit; }
$BASE = ''; $PAGE_TITLE = 'Login'; $HIDE_NAV = true;
include __DIR__ . '/partials/header.php';
?>
<div class="auth-topbar">
  <?php include __DIR__ . '/partials/lang_switch.php'; ?>
  <button class="theme-toggle" data-theme-toggle><span class="ic moon">🌙</span><span class="ic sun">☀️</span><span class="knob"></span></button>
</div>
<div class="center-wrap">
  <div class="panel glass auth-card">
    <div class="auth-head">
      <div class="logo-big"><?php readfile(__DIR__ . '/assets/img/logo.svg'); ?></div>
      <h1><span class="gradient-text" data-i18n="welcome">Selamat Datang</span></h1>
      <p data-i18n="welcome_sub">Masuk untuk mulai menonton & kumpulkan STAR ⭐</p>
    </div>
    <form id="loginForm" autocomplete="on">
      <div class="field">
        <label data-i18n="email">Email</label>
        <input class="input" type="email" name="email" placeholder="email@contoh.com" required>
      </div>
      <div class="field">
        <div class="row-between">
          <label data-i18n="password">Password</label>
          <a href="forgot.php" data-i18n="forgot" style="font-size:13px;color:var(--cyan);font-weight:700">Lupa password?</a>
        </div>
        <input class="input" type="password" name="password" placeholder="••••••••" required>
      </div>
      <button class="btn block" type="submit" data-i18n="cta_signin">Masuk</button>
    </form>
    <div class="auth-foot"><span data-i18n="no_account">Belum punya akun?</span> <a href="register.php" data-i18n="register_now">Daftar sekarang</a></div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/auth.js" defer></script>
