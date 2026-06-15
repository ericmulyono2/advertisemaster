<?php
require_once __DIR__ . '/../includes/functions.php';
csrf_check();
$u = require_login();

$old = field('old_password');
$new = field('new_password');

if (!password_verify($old, $u['password_hash'])) json_err('Password lama salah.');
if (strlen($new) < 6) json_err('Password baru minimal 6 karakter.');

db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
    ->execute([password_hash($new, PASSWORD_DEFAULT), $u['id']]);
json_ok([], 'Password berhasil diganti.');
