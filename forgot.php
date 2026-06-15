<?php
require_once __DIR__ . '/includes/functions.php';
if (current_user()) { header('Location: index.php'); exit; }
$BASE = ''; $PAGE_TITLE = 'Lupa Password'; $HIDE_NAV = true;
include __DIR__ . '/partials/header.php';
?>
<div class="center-wrap">
  <div class="panel glass auth-card">
    <div class="auth-head">
      <div class="logo-big"><?php readfile(__DIR__ . '/assets/img/logo.svg'); ?></div>
      <h1>Reset <span class="gradient-text">Password</span></h1>
      <p>Kami akan mengirim kode OTP ke email Anda</p>
    </div>

    <!-- Step 1: email -->
    <form id="fpEmailForm">
      <div class="field"><label>Email terdaftar</label>
        <input class="input" type="email" name="email" placeholder="email@contoh.com" required></div>
      <button class="btn block" type="submit">Kirim Kode OTP →</button>
    </form>

    <!-- Step 2: otp + new password -->
    <form id="fpResetForm" class="hidden">
      <p class="muted" style="font-size:13px;margin-top:0">Kode dikirim ke <b id="fpEmail"></b></p>
      <div class="otp-row" id="fpOtpInputs">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
        <input maxlength="1" inputmode="numeric"><input maxlength="1" inputmode="numeric">
      </div>
      <div class="field" style="margin-top:16px"><label>Password Baru</label>
        <input class="input" type="password" name="password" placeholder="Min. 6 karakter" required></div>
      <button class="btn block" type="submit">Simpan Password Baru</button>
    </form>

    <div class="auth-foot"><a href="login.php">← Kembali ke login</a></div>
  </div>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
<script src="assets/js/auth.js" defer></script>
