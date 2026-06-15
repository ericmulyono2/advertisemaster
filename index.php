<?php
require_once __DIR__ . '/includes/functions.php';
$u = current_user();
if ($u) {
    header('Location: ' . (is_panel_role($u['role']) ? 'admin/index.php' : 'dashboard.php'));
    exit;
}
$BASE = ''; $PAGE_TITLE = 'AdvertiseMaster'; $HIDE_NAV = true;
include __DIR__ . '/partials/header.php';
?>
<div class="container">
  <nav class="landing-nav">
    <a class="brand" href="index.php"><?php readfile(__DIR__ . '/assets/img/logo.svg'); ?></a>
    <div class="right">
      <?php include __DIR__ . '/partials/lang_switch.php'; ?>
      <button class="theme-toggle" data-theme-toggle title="Theme">
        <span class="ic moon">🌙</span><span class="ic sun">☀️</span><span class="knob"></span>
      </button>
      <a class="btn ghost sm" href="login.php" data-i18n="login">Sign in</a>
    </div>
  </nav>

  <section class="landing">
    <span class="pill">⚡ <span data-i18n="badge">Next-generation ad engagement platform</span></span>
    <h1 class="hero-title"><span data-i18n="hero1">Watch ads,</span> <span class="gradient-text" data-i18n="hero2">earn rewards</span></h1>
    <p class="hero-sub" data-i18n="subtitle">Join thousands of users earning stars daily by engaging with premium advertisements. Simple, rewarding, and completely free.</p>
    <div class="hero-cta">
      <a class="btn lg" href="register.php"><span data-i18n="cta_start">Get started free</span> →</a>
      <a class="btn ghost lg" href="login.php" data-i18n="cta_signin">Sign in</a>
    </div>

    <div class="features">
      <div class="feature c1">
        <div class="fico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.7 7-6.3-3.8L5.7 21l1.7-7L2 9.2l7.1-.6z"/></svg></div>
        <h3 data-i18n="f1_t">Earn stars</h3>
        <p data-i18n="f1_d">Watch ads and collect stars for every engagement</p>
      </div>
      <div class="feature c2">
        <div class="fico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 17l6-6 4 4 8-8"/><path d="M21 7v5h-5"/></svg></div>
        <h3 data-i18n="f2_t">Track progress</h3>
        <p data-i18n="f2_d">Monitor your earnings and engagement history</p>
      </div>
      <div class="feature c3">
        <div class="fico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="7" r="3"/><path d="M2 21v-1a5 5 0 0 1 5-5h4a5 5 0 0 1 5 5v1"/><circle cx="18" cy="8" r="2.4"/><path d="M22 21v-1a4 4 0 0 0-3-3.8"/></svg></div>
        <h3 data-i18n="f3_t">Join community</h3>
        <p data-i18n="f3_d">Be part of a growing network of engaged users</p>
      </div>
    </div>
  </section>
</div>
<?php include __DIR__ . '/partials/footer.php'; ?>
