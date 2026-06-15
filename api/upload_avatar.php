<?php
require_once __DIR__ . '/../includes/functions.php';
csrf_check();
$u = require_login();

if (empty($_FILES['avatar']) || $_FILES['avatar']['error'] !== UPLOAD_ERR_OK) {
    json_err('File tidak terkirim.');
}
$f = $_FILES['avatar'];
if ($f['size'] > 3 * 1024 * 1024) json_err('Ukuran maksimal 3MB.');

$allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp', 'image/gif' => 'gif'];
$mime = mime_content_type($f['tmp_name']);
if (!isset($allowed[$mime])) json_err('Format harus JPG/PNG/WEBP/GIF.');

$dir = __DIR__ . '/../assets/uploads';
if (!is_dir($dir)) @mkdir($dir, 0775, true);

$fname = 'u' . $u['id'] . '_' . time() . '.' . $allowed[$mime];
$dest = $dir . '/' . $fname;
if (!move_uploaded_file($f['tmp_name'], $dest)) json_err('Gagal menyimpan file.');

// delete old avatar file
if (!empty($u['avatar'])) {
    $old = $dir . '/' . basename($u['avatar']);
    if (is_file($old)) @unlink($old);
}

$rel = 'assets/uploads/' . $fname;
db()->prepare('UPDATE users SET avatar = ? WHERE id = ?')->execute([$rel, $u['id']]);
json_ok(['avatar' => $rel], 'Foto profil diperbarui.');
