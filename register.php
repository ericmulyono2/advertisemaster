<?php
require_once __DIR__ . '/includes/functions.php';
if (current_user()) { header('Location: index.php'); exit; }
$BASE = ''; $PAGE_TITLE = 'Daftar'; $HIDE_NAV = true;
include __DIR__ . '/partials/header.php';
?>
<div class="center-wrap">
  <div class="panel glass auth-card">
    <div class="auth-head">
      <div class="logo-big"><?php readfile(__DIR__ . '/assets/img/logo.svg'); ?></div>
      <h1>Buat <span class="gradient-text">Akun</span></h1>
      <p id="regSub">Daftar gratis & verifikasi email Anda</p>
    </div>

    <!-- Step 1: data -->
    <form id="regForm">
      <div class="field"><label>Nama Lengkap</label>
        <input class="input" name="name" placeholder="Nama Anda" required></div>
      <div class="field"><label>Email</label>
        <input class="input" type="email" name="email" placeholder="email@contoh.com" required></div>
      <div class="field"><label>Password</label>
        <input class="input" type="password" name="password" placeholder="Min. 6 karakter" required></div>
      <button class="btn block" type="submit">Kirim Kode OTP →</button>
    </form>

    <!-- Step 2: OTP -->
    <form id="otpForm" class="hidden">
      <p class="muted" style="font-size:13px;margin-top:0">Masukkan 6 digit kode yang dikirim ke <b id="otpEmail"></b></p>
      <div class="otp-row" id="otpInputs">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
      </div>
      <button class="btn block" type="submit" style="margin-top:18px">Verifikasi & Daftar</button>
      <div class="auth-foot"><a href="#" id="resendOtp">Kirim ulang kode</a></div>
    </form>

    <div class="auth-foot">Sudah punya akun? <a href="login.php">Masuk</a></div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/auth.js" defer></script>
