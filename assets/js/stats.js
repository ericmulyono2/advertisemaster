/* ============ AdvertiseMaster — user stats ============ */
(function () {
  const wrap = document.getElementById('histWrap');
  let filter = 'all';

  function groupByDate(rows) {
    const g = {};
    rows.forEach(r => {
      const d = new Date(r.created_at.replace(' ', 'T'));
      const key = d.toLocaleDateString('id-ID', { weekday:'long', day:'2-digit', month:'long', year:'numeric' });
      (g[key] = g[key] || []).push(r);
    });
    return g;
  }
  function rowHTML(r) {
    const badge = r.type === 'love'
      ? `<span class="badge love">❤ Favorit</span>`
      : `<span class="badge watch">▶ Ditonton</span>`;
    const star = r.type === 'watch' ? `<span class="reward" style="color:var(--warn);font-weight:800">+${r.stars_earned} ⭐</span>` : '';
    const t = new Date(r.created_at.replace(' ','T')).toLocaleTimeString('id-ID',{hour:'2-digit',minute:'2-digit'});
    return `<tr><td><b>#${String(r.ad_number).padStart(2,'0')}</b></td><td>${escapeHtml(r.title)}</td>
      <td>${badge}</td><td class="muted">${t}</td><td style="text-align:right">${star}</td></tr>`;
  }
  function escapeHtml(s){return (s||'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}

  async function load() {
    wrap.innerHTML = '<div class="spinner"></div>';
    try {
      const r = await AM.api('api/stats.php?type=' + filter, null, 'GET');
      document.getElementById('sStar').textContent = r.summary.star_total;
      document.getElementById('sWatch').textContent = r.summary.watch_count;
      document.getElementById('sLove').textContent = r.summary.love_count;
      if (!r.history.length) { wrap.innerHTML = `<div class="empty">Belum ada riwayat.</div>`; return; }
      const groups = groupByDate(r.history);
      wrap.innerHTML = Object.entries(groups).map(([date, rows]) => `
        <h4 style="margin:18px 0 8px;color:var(--muted);font-size:13px">${date}</h4>
        <table class="tbl"><tbody>${rows.map(rowHTML).join('')}</tbody></table>`).join('');
    } catch (e) { wrap.innerHTML = `<div class="empty">${e.message}</div>`; }
  }

  document.querySelectorAll('.tabs button').forEach(b =>
    b.addEventListener('click', () => {
      document.querySelectorAll('.tabs button').forEach(x => x.classList.remove('active'));
      b.classList.add('active'); filter = b.dataset.f; load();
    }));
  load();
})();
