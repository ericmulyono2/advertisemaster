/* ============ AdvertiseMaster — shared client utils ============ */
const AM = {
  csrf: document.querySelector('meta[name="csrf"]')?.content || '',
  base: document.querySelector('meta[name="base"]')?.content || '',

  /* ---- theme ---- */
  initTheme() {
    const saved = localStorage.getItem('am-theme') || 'light';
    document.documentElement.setAttribute('data-theme', saved);
  },
  toggleTheme() {
    const cur = document.documentElement.getAttribute('data-theme');
    const next = cur === 'light' ? 'dark' : 'light';
    document.documentElement.setAttribute('data-theme', next);
    localStorage.setItem('am-theme', next);
    if (window.gsap) gsap.fromTo('body', { opacity: .85 }, { opacity: 1, duration: .4 });
  },

  /* ---- toast ---- */
  toast(msg, type = 'ok') {
    let t = document.getElementById('toast');
    if (!t) { t = document.createElement('div'); t.id = 'toast'; document.body.appendChild(t); }
    t.textContent = msg; t.className = type;
    requestAnimationFrame(() => t.classList.add('show'));
    clearTimeout(this._tt);
    this._tt = setTimeout(() => t.classList.remove('show'), 3200);
  },

  /* ---- API ---- */
  async api(path, data = null, method = 'POST') {
    const opts = { method, headers: {} };
    if (data instanceof FormData) {
      data.append('csrf', this.csrf);
      opts.body = data;
    } else if (data !== null) {
      opts.headers['Content-Type'] = 'application/json';
      opts.headers['X-CSRF'] = this.csrf;
      opts.body = JSON.stringify({ ...data, csrf: this.csrf });
    } else {
      opts.method = 'GET';
    }
    const res = await fetch(this.base + path, opts);
    let json;
    try { json = await res.json(); }
    catch { throw new Error('Respon server tidak valid.'); }
    if (!json.success) throw new Error(json.message || 'Terjadi kesalahan.');
    return json;
  },

  /* ---- helpers ---- */
  fmtDate(s) {
    const d = new Date(s.replace(' ', 'T'));
    return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })
      + ' · ' + d.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
  },
  star(n) {
    return `<svg viewBox="0 0 24 24" width="14" height="14" fill="currentColor"><path d="M12 2l2.9 6.6 7.1.6-5.4 4.7 1.7 7-6.3-3.8L5.7 21l1.7-7L2 9.2l7.1-.6z"/></svg>`;
  },
};
AM.initTheme();
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-theme-toggle]').forEach(el =>
    el.addEventListener('click', () => AM.toggleTheme()));

  // entrance animations
  if (window.gsap) {
    gsap.from('.nav-in > *', { y: -16, opacity: 0, stagger: .06, duration: .5, ease: 'power3.out' });
    gsap.from('.auth-card', { y: 24, opacity: 0, duration: .6, ease: 'power3.out' });
  }
});
