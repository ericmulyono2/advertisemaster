<?php
$avatarHtml = !empty($u['avatar'])
  ? '<img class="avatar lg" id="avImg" src="' . e($BASE . $u['avatar']) . '">'
  : '<span class="avatar lg" id="avImg">' . e(strtoupper(mb_substr($u['name'],0,1))) . '</span>';
?>
<div class="container" style="max-width:900px;padding-top:28px;padding-bottom:60px">
  <div class="panel" style="margin-bottom:18px">
    <div class="profile-top">
      <div class="avatar-edit">
        <?= $avatarHtml ?>
        <label class="cam" title="Ganti foto">
          📷<input type="file" id="avInput" accept="image/*" hidden>
        </label>
      </div>
      <div class="meta">
        <h2 id="pName"><?= e($u['name']) ?></h2>
        <div class="id">ID Pengguna: <b>#<?= str_pad((string)$u['id'],5,'0',STR_PAD_LEFT) ?></b></div>
        <div class="id"><?= e($u['email']) ?> · <span class="badge <?= $u['role']==='admin'?'on':'watch' ?>"><?= e(ucfirst($u['role'])) ?></span></div>
        <?php if ($u['role'] !== 'admin'): ?>
        <div class="id" style="margin-top:6px">⭐ <b><?= (int)$u['stars'] ?></b> STAR terkumpul</div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <div class="grid-2">
    <div class="panel">
      <h3 class="section-title">Edit Profil</h3>
      <form id="profileForm">
        <div class="field"><label>Nama</label>
          <input class="input" name="name" value="<?= e($u['name']) ?>" required></div>
        <div class="field"><label>Email (tidak dapat diubah)</label>
          <input class="input" value="<?= e($u['email']) ?>" disabled></div>
        <button class="btn" type="submit">Simpan Perubahan</button>
      </form>
    </div>

    <div class="panel">
      <h3 class="section-title">Ganti Password</h3>
      <form id="passForm">
        <div class="field"><label>Password Lama</label>
          <input class="input" type="password" name="old_password" required></div>
        <div class="field"><label>Password Baru</label>
          <input class="input" type="password" name="new_password" placeholder="Min. 6 karakter" required></div>
        <button class="btn" type="submit">Ubah Password</button>
      </form>
      <hr style="border:none;border-top:1px solid var(--border);margin:22px 0">
      <a class="btn danger block" href="<?= e($BASE) ?>api/logout.php">⏻ Logout</a>
    </div>
  </div>
</div>
