/* ============ AdvertiseMaster — profile ============ */
(function () {
  const pf = document.getElementById('profileForm');
  if (pf) pf.addEventListener('submit', async e => {
    e.preventDefault();
    try {
      const r = await AM.api('api/profile_update.php', { name: pf.name.value });
      document.getElementById('pName').textContent = r.name;
      AM.toast(r.message, 'ok');
    } catch (err) { AM.toast(err.message, 'err'); }
  });

  const pw = document.getElementById('passForm');
  if (pw) pw.addEventListener('submit', async e => {
    e.preventDefault();
    try {
      const r = await AM.api('api/change_password.php', {
        old_password: pw.old_password.value, new_password: pw.new_password.value
      });
      pw.reset(); AM.toast(r.message, 'ok');
    } catch (err) { AM.toast(err.message, 'err'); }
  });

  const av = document.getElementById('avInput');
  if (av) av.addEventListener('change', async () => {
    if (!av.files[0]) return;
    const fd = new FormData(); fd.append('avatar', av.files[0]);
    try {
      const r = await AM.api('api/upload_avatar.php', fd);
      const img = document.getElementById('avImg');
      const fresh = document.createElement('img');
      fresh.className = 'avatar lg'; fresh.id = 'avImg';
      fresh.src = AM.base + r.avatar + '?t=' + Date.now();
      img.replaceWith(fresh);
      AM.toast(r.message, 'ok');
    } catch (err) { AM.toast(err.message, 'err'); }
  });
})();
