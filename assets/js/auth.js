/* ============ AdvertiseMaster — auth flows ============ */
(function () {
  // ---- OTP box UX (auto-advance + paste) ----
  function wireOtp(container) {
    if (!container) return;
    const boxes = [...container.querySelectorAll('input')];
    boxes.forEach((b, i) => {
      b.addEventListener('input', () => {
        b.value = b.value.replace(/\D/g, '').slice(0, 1);
        if (b.value && i < boxes.length - 1) boxes[i + 1].focus();
      });
      b.addEventListener('keydown', e => {
        if (e.key === 'Backspace' && !b.value && i > 0) boxes[i - 1].focus();
      });
      b.addEventListener('paste', e => {
        const d = (e.clipboardData.getData('text') || '').replace(/\D/g, '').slice(0, 6);
        if (d.length) { e.preventDefault(); d.split('').forEach((c, k) => { if (boxes[k]) boxes[k].value = c; }); boxes[Math.min(d.length, 5)].focus(); }
      });
    });
    return () => boxes.map(b => b.value).join('');
  }
  const busy = (btn, on) => { if (btn) { btn.disabled = on; btn.dataset._t = btn.dataset._t || btn.textContent; btn.textContent = on ? 'Memproses…' : btn.dataset._t; } };

  /* ---------- LOGIN ---------- */
  const loginForm = document.getElementById('loginForm');
  if (loginForm) loginForm.addEventListener('submit', async e => {
    e.preventDefault();
    const btn = loginForm.querySelector('button'); busy(btn, true);
    try {
      const r = await AM.api('api/login.php', {
        email: loginForm.email.value, password: loginForm.password.value
      });
      AM.toast(r.message, 'ok');
      setTimeout(() => location.href = r.redirect, 500);
    } catch (err) { AM.toast(err.message, 'err'); busy(btn, false); }
  });

  /* ---------- REGISTER ---------- */
  const regForm = document.getElementById('regForm');
  const otpForm = document.getElementById('otpForm');
  if (regForm) {
    const readOtp = wireOtp(document.getElementById('otpInputs'));
    let regEmail = '';
    regForm.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = regForm.querySelector('button'); busy(btn, true);
      try {
        const r = await AM.api('api/register.php', {
          name: regForm.name.value, email: regForm.email.value, password: regForm.password.value
        });
        regEmail = r.email;
        document.getElementById('otpEmail').textContent = r.email;
        regForm.classList.add('hidden'); otpForm.classList.remove('hidden');
        document.getElementById('regSub').textContent = 'Cek email Anda untuk kode OTP';
        AM.toast(r.message + (r.debug_otp ? ' (OTP debug: ' + r.debug_otp + ')' : ''), 'ok');
        document.querySelector('#otpInputs input')?.focus();
      } catch (err) { AM.toast(err.message, 'err'); busy(btn, false); }
    });
    otpForm.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = otpForm.querySelector('button[type=submit]'); busy(btn, true);
      try {
        const r = await AM.api('api/verify_register.php', { email: regEmail, otp: readOtp() });
        AM.toast(r.message, 'ok');
        setTimeout(() => location.href = r.redirect, 600);
      } catch (err) { AM.toast(err.message, 'err'); busy(btn, false); }
    });
    document.getElementById('resendOtp')?.addEventListener('click', async e => {
      e.preventDefault();
      try {
        const r = await AM.api('api/register.php', {
          name: regForm.name.value, email: regForm.email.value, password: regForm.password.value
        });
        AM.toast('Kode dikirim ulang.' + (r.debug_otp ? ' (OTP: ' + r.debug_otp + ')' : ''), 'ok');
      } catch (err) { AM.toast(err.message, 'err'); }
    });
  }

  /* ---------- FORGOT PASSWORD ---------- */
  const fpEmailForm = document.getElementById('fpEmailForm');
  const fpResetForm = document.getElementById('fpResetForm');
  if (fpEmailForm) {
    const readOtp = wireOtp(document.getElementById('fpOtpInputs'));
    let fpEmail = '';
    fpEmailForm.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = fpEmailForm.querySelector('button'); busy(btn, true);
      try {
        const r = await AM.api('api/forgot_password.php', { email: fpEmailForm.email.value });
        fpEmail = r.email;
        document.getElementById('fpEmail').textContent = r.email;
        fpEmailForm.classList.add('hidden'); fpResetForm.classList.remove('hidden');
        AM.toast(r.message + (r.debug_otp ? ' (OTP: ' + r.debug_otp + ')' : ''), 'ok');
        document.querySelector('#fpOtpInputs input')?.focus();
      } catch (err) { AM.toast(err.message, 'err'); busy(btn, false); }
    });
    fpResetForm.addEventListener('submit', async e => {
      e.preventDefault();
      const btn = fpResetForm.querySelector('button'); busy(btn, true);
      try {
        const r = await AM.api('api/reset_password.php', {
          email: fpEmail, otp: readOtp(), password: fpResetForm.password.value
        });
        AM.toast(r.message, 'ok');
        setTimeout(() => location.href = r.redirect, 700);
      } catch (err) { AM.toast(err.message, 'err'); busy(btn, false); }
    });
  }
})();
