<?php
require_once __DIR__ . '/../includes/functions.php';
$u = current_user();
if (!$u) { header('Location: ../login.php'); exit; }
if (!is_panel_role($u['role'])) { header('Location: ../dashboard.php'); exit; }
$BASE = '../'; $PAGE_TITLE = 'Pengguna'; $ACTIVE = 'users';
include __DIR__ . '/../partials/header.php';
?>
<div class="container" style="padding-top:28px;padding-bottom:60px">
  <div class="grid-head">
    <h2 style="margin:0">Database <span class="gradient-text">Pengguna</span></h2>
    <div style="display:flex;gap:10px;align-items:center;flex-wrap:wrap">
      <input class="input" id="userSearch" placeholder="Cari nama / email…" style="max-width:240px">
      <button class="btn sm" id="btnCreateUser">+ Buat Akun</button>
    </div>
  </div>
  <div class="panel"><div id="usersWrap"><div class="spinner"></div></div></div>
</div>

<!-- user detail modal -->
<div class="modal modal-sm" id="userModal">
  <div class="modal-card" style="max-width:680px">
    <div class="player-top"><b id="umName">User</b><button class="x" id="umClose">✕</button></div>
    <div class="pad" style="padding:22px">
      <div class="grid-2" style="margin-bottom:18px">
        <div>
          <div class="muted" style="font-size:13px">Email</div><div id="umEmail" style="font-weight:700"></div>
          <div class="muted" style="font-size:13px;margin-top:10px">⭐ STAR</div><div id="umStars" style="font-weight:800;color:var(--warn)"></div>
        </div>
        <div>
          <form id="umPassForm">
            <div class="field"><label>Set Password Baru</label>
              <input class="input" type="text" name="new_password" placeholder="Min. 6 karakter"></div>
            <button class="btn sm" type="submit">Ganti Password User</button>
          </form>
        </div>
      </div>
      <h4 class="section-title">Riwayat Tayangan</h4>
      <div id="umHistory" style="max-height:340px;overflow:auto"></div>
    </div>
  </div>
</div>
<!-- create user modal -->
<div class="modal modal-sm" id="createModal">
  <div class="modal-card">
    <div class="player-top"><b>Buat Akun Baru</b><button class="x" id="cuClose">✕</button></div>
    <div class="pad" style="padding:24px">
      <form id="createForm">
        <div class="field"><label>Nama</label><input class="input" name="name" required></div>
        <div class="field"><label>Email</label><input class="input" type="email" name="email" required></div>
        <div class="field"><label>Password</label><input class="input" type="text" name="password" placeholder="Min. 6 karakter" required></div>
        <div class="field"><label>Role</label>
          <select class="input" name="role">
            <option value="user">User (penonton)</option>
            <?php if ($u['role'] === 'admin'): ?><option value="staff">Staff (pekerja)</option><?php endif; ?>
          </select>
        </div>
        <button class="btn block" type="submit">Buat Akun</button>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
<script src="../assets/js/admin.js" defer></script>
<script>document.addEventListener('DOMContentLoaded',()=>AdminUsers());</script>
