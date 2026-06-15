/* ============ AdvertiseMaster — admin ============ */
function escapeHtml(s){return (s||'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));}
function fmtDate(s){const d=new Date(s.replace(' ','T'));return d.toLocaleDateString('id-ID',{day:'2-digit',month:'short',year:'numeric'});}

/* ---------------- DASHBOARD ---------------- */
async function AdminDashboard() {
  try {
    const r = await AM.api('api/admin/dashboard.php', null, 'GET');
    const s = r.stats;
    const cards = [
      ['👥', s.totalUsers, 'Total Pengguna'],
      ['📺', s.totalAds, 'Total Iklan'],
      ['🟢', s.activeAds, 'Iklan Aktif'],
      ['▶️', s.totalWatch, 'Tayangan Ditonton'],
      ['❤️', s.totalLove, 'Total Love'],
      ['⭐', s.totalStars, 'STAR Terdistribusi'],
    ];
    document.getElementById('statCards').innerHTML = cards.map(c =>
      `<div class="stat-card"><div class="ic">${c[0]}</div><div class="v">${c[1]}</div><div class="l">${c[2]}</div></div>`).join('');

    // bars
    const days = []; const today = new Date();
    for (let i = 6; i >= 0; i--) { const d = new Date(today); d.setDate(d.getDate()-i);
      days.push({ key: d.toISOString().slice(0,10), lbl: d.toLocaleDateString('id-ID',{weekday:'short'}) }); }
    const map = {}; r.daily.forEach(x => map[x.d] = +x.c);
    const max = Math.max(1, ...Object.values(map));
    document.getElementById('bars').innerHTML = days.map(d => {
      const v = map[d.key] || 0;
      return `<div class="bar" style="height:0" data-h="${Math.max(4, v/max*100)}"><b>${v}</b><span>${d.lbl}</span></div>`;
    }).join('');
    requestAnimationFrame(() => document.querySelectorAll('#bars .bar').forEach(b => b.style.height = b.dataset.h + '%'));

    document.getElementById('recent').innerHTML = r.recent.length
      ? `<table class="tbl"><tbody>${r.recent.map(u =>
          `<tr><td>${escapeHtml(u.name)}</td><td class="muted">${escapeHtml(u.email)}</td><td style="text-align:right" class="muted">${fmtDate(u.created_at)}</td></tr>`).join('')}</tbody></table>`
      : `<div class="empty">Belum ada pendaftar.</div>`;
  } catch (e) { AM.toast(e.message, 'err'); }
}

/* ---------------- USERS ---------------- */
async function AdminUsers() {
  const wrap = document.getElementById('usersWrap');
  const search = document.getElementById('userSearch');
  let timer;

  async function load(q = '') {
    wrap.innerHTML = '<div class="spinner"></div>';
    try {
      const r = await AM.api('api/admin/users.php' + (q ? '?q=' + encodeURIComponent(q) : ''), null, 'GET');
      if (!r.users.length) { wrap.innerHTML = `<div class="empty">Tidak ada user.</div>`; return; }
      wrap.innerHTML = `<table class="tbl"><thead><tr><th>ID</th><th>Nama</th><th>Email</th><th>Role</th><th>STAR</th><th>Daftar</th><th></th></tr></thead><tbody>${
        r.users.map(u => `<tr>
          <td>#${String(u.id).padStart(5,'0')}</td>
          <td><b>${escapeHtml(u.name)}</b></td>
          <td class="muted">${escapeHtml(u.email)}</td>
          <td><span class="badge ${u.role==='user'?'watch':(u.role==='staff'?'staff':'on')}">${u.role}</span></td>
          <td style="color:var(--warn);font-weight:800">${u.stars}</td>
          <td class="muted">${fmtDate(u.created_at)}</td>
          <td style="text-align:right">${u.role==='user'?`<button class="btn sm" data-view="${u.id}">Detail</button>`:''}</td>
        </tr>`).join('')}</tbody></table>`;
    } catch (e) { wrap.innerHTML = `<div class="empty">${e.message}</div>`; }
  }
  search.addEventListener('input', () => { clearTimeout(timer); timer = setTimeout(() => load(search.value.trim()), 300); });

  // modal
  const modal = document.getElementById('userModal');
  let currentUid = null;
  document.getElementById('umClose').addEventListener('click', () => modal.classList.remove('open'));
  modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });

  wrap.addEventListener('click', e => {
    const v = e.target.closest('[data-view]'); if (v) openUser(+v.dataset.view);
  });

  async function openUser(uid) {
    currentUid = uid;
    modal.classList.add('open');
    document.getElementById('umHistory').innerHTML = '<div class="spinner"></div>';
    try {
      const r = await AM.api('api/admin/user_history.php?user_id=' + uid, null, 'GET');
      document.getElementById('umName').textContent = r.user.name;
      document.getElementById('umEmail').textContent = r.user.email;
      document.getElementById('umStars').textContent = r.user.stars + ' ⭐';
      renderHist(r.history);
    } catch (e) { AM.toast(e.message, 'err'); }
  }
  function renderHist(history) {
    const el = document.getElementById('umHistory');
    if (!history.length) { el.innerHTML = `<div class="empty">Belum ada riwayat.</div>`; return; }
    el.innerHTML = `<table class="tbl"><thead><tr><th>No</th><th>Judul</th><th>Tipe</th><th>Tanggal</th><th>STAR</th><th></th></tr></thead><tbody>${
      history.map(h => `<tr>
        <td>#${String(h.ad_number).padStart(2,'0')}</td>
        <td>${escapeHtml(h.title)}</td>
        <td><span class="badge ${h.type==='love'?'love':'watch'}">${h.type==='love'?'❤ Love':'▶ Tonton'}</span></td>
        <td class="muted">${fmtDate(h.created_at)}</td>
        <td style="color:var(--warn)">${h.stars_earned?('+'+h.stars_earned):'-'}</td>
        <td style="text-align:right"><button class="btn sm danger" data-del="${h.id}">Hapus</button></td>
      </tr>`).join('')}</tbody></table>`;
    el.querySelectorAll('[data-del]').forEach(b => b.addEventListener('click', async () => {
      if (!confirm('Hapus riwayat ini?')) return;
      try { await AM.api('api/admin/delete_history.php', { id: +b.dataset.del }); openUser(currentUid); AM.toast('Dihapus.', 'ok'); }
      catch (e) { AM.toast(e.message, 'err'); }
    }));
  }
  document.getElementById('umPassForm').addEventListener('submit', async e => {
    e.preventDefault();
    const np = e.target.new_password.value;
    try {
      await AM.api('api/admin/set_user_password.php', { user_id: currentUid, new_password: np });
      e.target.reset(); AM.toast('Password user diganti.', 'ok');
    } catch (err) { AM.toast(err.message, 'err'); }
  });

  // ---- create user/staff ----
  const createModal = document.getElementById('createModal');
  const createForm = document.getElementById('createForm');
  document.getElementById('btnCreateUser').addEventListener('click', () => createModal.classList.add('open'));
  document.getElementById('cuClose').addEventListener('click', () => createModal.classList.remove('open'));
  createModal.addEventListener('click', e => { if (e.target === createModal) createModal.classList.remove('open'); });
  createForm.addEventListener('submit', async e => {
    e.preventDefault();
    try {
      const r = await AM.api('api/admin/create_user.php', {
        name: createForm.name.value, email: createForm.email.value,
        password: createForm.password.value, role: createForm.role.value
      });
      createModal.classList.remove('open'); createForm.reset();
      AM.toast(r.message, 'ok'); load();
    } catch (err) { AM.toast(err.message, 'err'); }
  });

  load();
}

/* ---------------- ADS ---------------- */
async function AdminAds() {
  const wrap = document.getElementById('adsWrap');
  const modal = document.getElementById('adModal');
  const form = document.getElementById('adForm');
  document.getElementById('adClose').addEventListener('click', () => modal.classList.remove('open'));
  modal.addEventListener('click', e => { if (e.target === modal) modal.classList.remove('open'); });

  async function load() {
    wrap.innerHTML = '<div class="spinner"></div>';
    try {
      const r = await AM.api('api/admin/ads_list.php', null, 'GET');
      document.getElementById('defStar').value = r.default_star;
      wrap.innerHTML = `<table class="tbl"><thead><tr><th>No</th><th>Judul</th><th>Durasi</th><th>STAR</th><th>Status</th><th>Aktif</th><th></th></tr></thead><tbody>${
        r.ads.map(a => `<tr data-id="${a.id}">
          <td><b>#${String(a.ad_number).padStart(2,'0')}</b></td>
          <td>${escapeHtml(a.title)}</td>
          <td class="muted">${a.duration}s</td>
          <td style="color:var(--warn);font-weight:800">${a.star_reward} ⭐</td>
          <td><span class="badge ${a.is_active==1?'on':'off'}">${a.is_active==1?'ON':'OFF'}</span></td>
          <td><label class="switch"><input type="checkbox" data-toggle ${a.is_active==1?'checked':''}><span class="sl"></span></label></td>
          <td style="text-align:right"><button class="btn sm ghost" data-edit='${encodeURIComponent(JSON.stringify(a))}'>Edit</button></td>
        </tr>`).join('')}</tbody></table>`;
      bind();
    } catch (e) { wrap.innerHTML = `<div class="empty">${e.message}</div>`; }
  }
  function bind() {
    wrap.querySelectorAll('[data-toggle]').forEach(cb => cb.addEventListener('change', async () => {
      const id = +cb.closest('tr').dataset.id;
      try {
        const r = await AM.api('api/admin/ad_toggle.php', { id, is_active: cb.checked ? 1 : 0 });
        const badge = cb.closest('tr').querySelector('.badge');
        badge.className = 'badge ' + (r.is_active ? 'on' : 'off');
        badge.textContent = r.is_active ? 'ON' : 'OFF';
        AM.toast(r.message, 'ok');
      } catch (e) { cb.checked = !cb.checked; AM.toast(e.message, 'err'); }
    }));
    wrap.querySelectorAll('[data-edit]').forEach(b => b.addEventListener('click', () => {
      const a = JSON.parse(decodeURIComponent(b.dataset.edit));
      form.id.value = a.id; form.title.value = a.title; form.video_url.value = a.video_url;
      form.duration.value = a.duration; form.star_reward.value = a.star_reward;
      modal.classList.add('open');
    }));
  }
  form.addEventListener('submit', async e => {
    e.preventDefault();
    try {
      const r = await AM.api('api/admin/ad_update.php', {
        id: +form.id.value, title: form.title.value, video_url: form.video_url.value,
        duration: +form.duration.value, star_reward: +form.star_reward.value
      });
      modal.classList.remove('open'); AM.toast(r.message, 'ok'); load();
    } catch (err) { AM.toast(err.message, 'err'); }
  });
  document.getElementById('applyAll').addEventListener('click', async () => {
    const v = +document.getElementById('defStar').value;
    if (!confirm('Terapkan STAR = ' + v + ' ke SEMUA iklan?')) return;
    try { const r = await AM.api('api/admin/set_default_star.php', { default_star: v, apply_all: 1 }); AM.toast(r.message, 'ok'); load(); }
    catch (e) { AM.toast(e.message, 'err'); }
  });
  load();
}
