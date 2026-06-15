/* ============ AdvertiseMaster — ad grid + player ============ */
(function () {
  const gridEl = document.getElementById('grid');
  let ads = [];

  const heartSVG = `<svg viewBox="0 0 24 24"><path d="M12 21s-7.5-4.6-10-8.9C-0.2 7.7 2.6 4 6.3 4c2 0 3.7 1.2 4.7 2.6C12 5.2 13.7 4 15.7 4 19.4 4 22.2 7.7 22 12.1 19.5 16.4 12 21 12 21z"/></svg>`;
  const playSVG = `<svg viewBox="0 0 24 24" width="20" height="20" fill="#fff"><path d="M8 5v14l11-7z"/></svg>`;

  function cardHTML(a) {
    const off = a.is_active ? '' : 'off';
    return `<div class="ad-card ${off}" data-id="${a.id}" data-active="${a.is_active}">
      <div class="ad-thumb" data-play>
        <span class="num">#${String(a.ad_number).padStart(2,'0')}</span>
        ${a.is_active ? `<div class="play">${playSVG}</div>` : ''}
        ${a.is_active ? '' : `<div class="lock">🔒 Segera</div>`}
        <span class="dur">${a.duration}s</span>
      </div>
      <div class="ad-body">
        <div class="t">${escapeHtml(a.title)}</div>
        <div class="meta">
          <span class="reward">${AM.star()} ${a.star_reward}</span>
          ${a.is_active ? `<button class="love-btn" data-love title="Suka & ganti">${heartSVG}</button>` : ''}
        </div>
      </div>
    </div>`;
  }
  function escapeHtml(s){return (s||'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}

  function render() {
    if (!ads.length) { gridEl.innerHTML = `<div class="empty">Belum ada iklan tersedia.</div>`; return; }
    gridEl.innerHTML = ads.map(cardHTML).join('');
    const cards = gridEl.querySelectorAll('.ad-card');
    if (window.gsap) gsap.to(cards, { opacity:1, y:0, stagger:.03, duration:.4, ease:'power2.out' });
    else cards.forEach(c=>c.classList.add('in'));
  }

  async function load() {
    gridEl.innerHTML = '<div class="spinner"></div>';
    try {
      const r = await AM.api('api/get_ads.php', null, 'GET');
      ads = r.ads;
      const ns = document.getElementById('navStars'); if (ns) ns.textContent = r.stars;
      render();
    } catch (e) { gridEl.innerHTML = `<div class="empty">${e.message}</div>`; }
  }

  // ---- grid clicks (delegated) ----
  gridEl.addEventListener('click', e => {
    const card = e.target.closest('.ad-card'); if (!card) return;
    const id = +card.dataset.id;
    const ad = ads.find(a => a.id === id); if (!ad) return;

    if (e.target.closest('[data-love]')) {
      loveAd(ad, card);
    } else if (e.target.closest('[data-play]') && +card.dataset.active === 1) {
      openPlayer(ad);
    } else if (+card.dataset.active !== 1) {
      AM.toast('Iklan ini belum diaktifkan admin.', 'err');
    }
  });

  async function loveAd(ad, card) {
    const btn = card.querySelector('[data-love]');
    btn.classList.add('loved');
    try {
      const exclude = ads.map(a => a.id);
      const r = await AM.api('api/toggle_love.php', { ad_id: ad.id, exclude });
      AM.toast(r.message, 'ok');
      // swap card out with replacement
      const idx = ads.findIndex(a => a.id === ad.id);
      const fade = () => {
        if (r.replacement) {
          ads[idx] = r.replacement;
          const tmp = document.createElement('div');
          tmp.innerHTML = cardHTML(r.replacement);
          const newCard = tmp.firstElementChild;
          card.replaceWith(newCard);
          if (window.gsap) gsap.fromTo(newCard, {opacity:0, scale:.85}, {opacity:1, scale:1, duration:.4});
          else newCard.classList.add('in');
        } else {
          ads.splice(idx, 1); card.remove();
        }
      };
      if (window.gsap) gsap.to(card, {opacity:0, scale:.85, duration:.3, onComplete:fade});
      else fade();
    } catch (e) { btn.classList.remove('loved'); AM.toast(e.message, 'err'); }
  }

  /* ---------- PLAYER ---------- */
  const modal = document.getElementById('player');
  const video = document.getElementById('pvVideo');
  const fill  = document.getElementById('pvFill');
  let timer = null, current = null, claimed = false, elapsed = 0;

  function openPlayer(ad) {
    current = ad; claimed = false; elapsed = 0;
    document.getElementById('pvTitle').textContent = ad.title;
    document.getElementById('pvNum').textContent = '#' + String(ad.ad_number).padStart(2,'0');
    document.getElementById('pvDur').textContent = ad.duration;
    document.getElementById('pvReward').textContent = ad.star_reward;
    document.getElementById('pvCount').textContent = ad.duration;
    fill.style.width = '0%';
    video.src = ad.video_url; video.currentTime = 0;
    modal.classList.add('open');
    video.play().catch(()=>{});
    clearInterval(timer);
    timer = setInterval(tick, 250);
  }
  function tick() {
    elapsed += 0.25;
    const need = current.duration;
    const pct = Math.min(100, elapsed / need * 100);
    fill.style.width = pct + '%';
    document.getElementById('pvCount').textContent = Math.max(0, Math.ceil(need - elapsed));
    if (elapsed >= need && !claimed) { claimed = true; claim(); }
  }
  async function claim() {
    clearInterval(timer);
    try {
      const r = await AM.api('api/watch_complete.php', { ad_id: current.id });
      AM.toast(r.message, 'ok');
      const ns = document.getElementById('navStars'); if (ns) ns.textContent = r.stars;
      if (window.gsap && ns) gsap.fromTo(ns.parentElement, {scale:1.4}, {scale:1, duration:.5, ease:'elastic.out(1,.4)'});
    } catch (e) { AM.toast(e.message, 'err'); }
  }
  function closePlayer() {
    clearInterval(timer); video.pause(); video.src=''; modal.classList.remove('open');
  }
  document.getElementById('pvClose').addEventListener('click', closePlayer);
  modal.addEventListener('click', e => { if (e.target === modal) closePlayer(); });
  video.addEventListener('ended', () => { if (!claimed) { claimed = true; claim(); } });

  document.getElementById('reloadGrid').addEventListener('click', load);
  load();
})();
