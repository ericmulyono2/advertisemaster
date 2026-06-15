<?php
// Step 2 forgot password: verify OTP + set new password
require_once __DIR__ . '/../includes/functions.php';
csrf_check();

$email = strtolower(field('email'));
$code  = field('otp');
$pass  = field('password');

if (strlen($pass) < 6) json_err('Password baru minimal 6 karakter.');

$st = db()->prepare('SELECT * FROM otps WHERE email = ? AND purpose = ? ORDER BY id DESC LIMIT 1');
$st->execute([$email, 'reset']);
$row = $st->fetch();

if (!$row)                                  json_err('Permintaan tidak ditemukan.');
if (strtotime($row['expires_at']) < time()) json_err('OTP kadaluarsa.');
if (!hash_equals($row['code'], $code))      json_err('Kode OTP salah.');

$upd = db()->prepare('UPDATE users SET password_hash = ? WHERE email = ?');
$upd->execute([password_hash($pass, PASSWORD_DEFAULT), $email]);

db()->prepare('DELETE FROM otps WHERE email = ? AND purpose = ?')->execute([$email, 'reset']);
json_ok(['redirect' => 'login.php'], 'Password berhasil direset. Silakan login.');
