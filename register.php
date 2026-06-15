<?php
require_once __DIR__ . '/includes/functions.php';
if (current_user()) { header('Location: index.php'); exit; }
$BASE = ''; $PAGE_TITLE = 'Daftar'; $HIDE_NAV = true;
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
      <h1><span class="gradient-text" data-i18n="create_account">Buat Akun</span></h1>
      <p id="regSub" data-i18n="create_sub">Daftar gratis & verifikasi email Anda</p>
    </div>

    <!-- Step 1: data -->
    <form id="regForm">
      <div class="field"><label data-i18n="full_name">Nama Lengkap</label>
        <input class="input" name="name" placeholder="Nama Anda" required></div>
      <div class="field"><label data-i18n="email">Email</label>
        <input class="input" type="email" name="email" placeholder="email@contoh.com" required></div>
      <div class="field"><label data-i18n="password">Password</label>
        <input class="input" type="password" name="password" placeholder="Min. 6 karakter" required></div>
      <button class="btn block" type="submit" data-i18n="send_otp">Kirim Kode OTP</button>
    </form>

    <!-- Step 2: OTP -->
    <form id="otpForm" class="hidden">
      <p class="muted" style="font-size:13px;margin-top:0"><span data-i18n="otp_sent_to">Masukkan 6 digit kode yang dikirim ke</span> <b id="otpEmail"></b></p>
      <div class="otp-row" id="otpInputs">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
      </div>
      <button class="btn block" type="submit" style="margin-top:18px" data-i18n="verify_register">Verifikasi & Daftar</button>
      <div class="auth-foot"><a href="#" id="resendOtp" data-i18n="resend">Kirim ulang kode</a></div>
    </form>

    <div class="auth-foot"><span data-i18n="have_account">Sudah punya akun?</span> <a href="login.php" data-i18n="login">Masuk</a></div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/auth.js" defer></script>
