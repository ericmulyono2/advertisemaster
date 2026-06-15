<?php
// Admin sets a new password for a user.
require_once __DIR__ . '/../../includes/functions.php';
csrf_check();
require_admin();

$uid = (int)field('user_id');
$new = field('new_password');
if (strlen($new) < 6) json_err('Password minimal 6 karakter.');

$st = db()->prepare('SELECT id FROM users WHERE id = ?');
$st->execute([$uid]);
if (!$st->fetch()) json_err('User tidak ditemukan.');

db()->prepare('UPDATE users SET password_hash = ? WHERE id = ?')
    ->execute([password_hash($new, PASSWORD_DEFAULT), $uid]);
json_ok([], 'Password user diperbarui.');
