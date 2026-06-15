/* ============ AdvertiseMaster — i18n (ID / EN / 中文) ============ */
const I18N = {
  en: {
    _label: 'English', _flag: 'GB',
    nav_home: 'Dashboard', nav_stats: 'Statistics', nav_profile: 'Profile',
    nav_dashboard: 'Dashboard', nav_users: 'Users', nav_ads: 'Ads',
    login: 'Sign in', logout: 'Log out',
    badge: 'Next-generation ad engagement platform',
    hero1: 'Watch ads,', hero2: 'earn rewards',
    subtitle: 'Join thousands of users earning stars daily by engaging with premium advertisements. Simple, rewarding, and completely free.',
    cta_start: 'Get started free', cta_signin: 'Sign in',
    f1_t: 'Earn stars', f1_d: 'Watch ads and collect stars for every engagement',
    f2_t: 'Track progress', f2_d: 'Monitor your earnings and engagement history',
    f3_t: 'Join community', f3_d: 'Be part of a growing network of engaged users',
    welcome: 'Welcome', welcome_sub: 'Sign in to start watching & collect stars ⭐',
    email: 'Email', password: 'Password', forgot: 'Forgot password?',
    no_account: "Don't have an account?", register_now: 'Sign up now',
    have_account: 'Already have an account?',
    create_account: 'Create Account', create_sub: 'Register free & verify your email',
    full_name: 'Full Name', send_otp: 'Send OTP code', verify_register: 'Verify & Register',
    resend: 'Resend code', otp_sent_to: 'Enter the 6-digit code sent to',
    reset_pw: 'Reset Password', reset_sub: "We'll send an OTP code to your email",
    new_password: 'New Password', save_new_pw: 'Save New Password', back_login: '← Back to login',
    registered_email: 'Registered email',
    grid_title: 'Ad Gallery', grid_sub: 'Watch to the end to earn stars ⭐ · tap ❤ to favorite & swap',
    reshuffle: 'Reshuffle', stars: 'STAR',
  },
  id: {
    _label: 'Bahasa Indonesia', _flag: 'ID',
    nav_home: 'Beranda', nav_stats: 'Statistik', nav_profile: 'Profil',
    nav_dashboard: 'Dasbor', nav_users: 'Pengguna', nav_ads: 'Iklan',
    login: 'Masuk', logout: 'Keluar',
    badge: 'Platform engagement iklan generasi baru',
    hero1: 'Tonton iklan,', hero2: 'raih hadiah',
    subtitle: 'Bergabunglah dengan ribuan pengguna yang mengumpulkan STAR setiap hari dengan menonton iklan premium. Sederhana, menguntungkan, dan sepenuhnya gratis.',
    cta_start: 'Mulai gratis', cta_signin: 'Masuk',
    f1_t: 'Kumpulkan STAR', f1_d: 'Tonton iklan dan kumpulkan STAR di setiap tayangan',
    f2_t: 'Pantau progres', f2_d: 'Lihat perolehan dan riwayat tayangan Anda',
    f3_t: 'Gabung komunitas', f3_d: 'Jadi bagian dari jaringan pengguna yang terus berkembang',
    welcome: 'Selamat Datang', welcome_sub: 'Masuk untuk mulai menonton & kumpulkan STAR ⭐',
    email: 'Email', password: 'Password', forgot: 'Lupa password?',
    no_account: 'Belum punya akun?', register_now: 'Daftar sekarang',
    have_account: 'Sudah punya akun?',
    create_account: 'Buat Akun', create_sub: 'Daftar gratis & verifikasi email Anda',
    full_name: 'Nama Lengkap', send_otp: 'Kirim Kode OTP', verify_register: 'Verifikasi & Daftar',
    resend: 'Kirim ulang kode', otp_sent_to: 'Masukkan 6 digit kode yang dikirim ke',
    reset_pw: 'Reset Password', reset_sub: 'Kami akan mengirim kode OTP ke email Anda',
    new_password: 'Password Baru', save_new_pw: 'Simpan Password Baru', back_login: '← Kembali ke login',
    registered_email: 'Email terdaftar',
    grid_title: 'Galeri Iklan', grid_sub: 'Tonton sampai selesai untuk dapat STAR ⭐ · tekan ❤ untuk favorit & ganti',
    reshuffle: 'Acak Ulang', stars: 'STAR',
  },
  zh: {
    _label: '中文', _flag: 'CN',
    nav_home: '仪表盘', nav_stats: '统计', nav_profile: '个人资料',
    nav_dashboard: '仪表盘', nav_users: '用户', nav_ads: '广告',
    login: '登录', logout: '退出',
    badge: '新一代广告互动平台',
    hero1: '观看广告，', hero2: '赢取奖励',
    subtitle: '加入数千名用户，通过观看优质广告每天赚取星星。简单、有回报，且完全免费。',
    cta_start: '免费开始', cta_signin: '登录',
    f1_t: '赚取星星', f1_d: '观看广告，每次互动都能收集星星',
    f2_t: '追踪进度', f2_d: '查看您的收益与观看历史',
    f3_t: '加入社区', f3_d: '成为不断壮大的用户网络的一员',
    welcome: '欢迎', welcome_sub: '登录即可开始观看并收集星星 ⭐',
    email: '邮箱', password: '密码', forgot: '忘记密码？',
    no_account: '还没有账号？', register_now: '立即注册',
    have_account: '已有账号？',
    create_account: '创建账号', create_sub: '免费注册并验证邮箱',
    full_name: '全名', send_otp: '发送验证码', verify_register: '验证并注册',
    resend: '重新发送', otp_sent_to: '请输入发送至以下邮箱的6位验证码',
    reset_pw: '重置密码', reset_sub: '我们将向您的邮箱发送验证码',
    new_password: '新密码', save_new_pw: '保存新密码', back_login: '← 返回登录',
    registered_email: '注册邮箱',
    grid_title: '广告库', grid_sub: '看完即可获得星星 ⭐ · 点 ❤ 收藏并更换',
    reshuffle: '重新随机', stars: '星',
  },
};

const Lang = {
  get() { return localStorage.getItem('am-lang') || (navigator.language || 'en').slice(0,2).replace('in','id'); },
  cur() { const l = this.get(); return I18N[l] ? l : 'en'; },
  t(key) { return (I18N[this.cur()] && I18N[this.cur()][key]) || (I18N.en[key]) || key; },
  set(l) { if (!I18N[l]) return; localStorage.setItem('am-lang', l); this.apply(); document.documentElement.lang = l; },
  apply() {
    const d = I18N[this.cur()];
    document.querySelectorAll('[data-i18n]').forEach(el => {
      const k = el.getAttribute('data-i18n'); if (d[k] !== undefined) el.textContent = d[k];
    });
    document.querySelectorAll('[data-i18n-ph]').forEach(el => {
      const k = el.getAttribute('data-i18n-ph'); if (d[k] !== undefined) el.placeholder = d[k];
    });
    document.querySelectorAll('[data-lang-current]').forEach(el => el.textContent = this.cur().toUpperCase());
    document.querySelectorAll('[data-lang-opt]').forEach(el =>
      el.classList.toggle('active', el.getAttribute('data-lang-opt') === this.cur()));
  },
};

document.addEventListener('DOMContentLoaded', () => {
  document.documentElement.lang = Lang.cur();
  Lang.apply();
  // language dropdown toggles
  document.querySelectorAll('[data-lang-toggle]').forEach(btn =>
    btn.addEventListener('click', e => {
      e.stopPropagation();
      const menu = btn.parentElement.querySelector('[data-lang-menu]');
      if (menu) menu.classList.toggle('open');
    }));
  document.querySelectorAll('[data-lang-opt]').forEach(opt =>
    opt.addEventListener('click', () => {
      Lang.set(opt.getAttribute('data-lang-opt'));
      document.querySelectorAll('[data-lang-menu]').forEach(m => m.classList.remove('open'));
    }));
  document.addEventListener('click', () =>
    document.querySelectorAll('[data-lang-menu]').forEach(m => m.classList.remove('open')));
});
