<?php
// Step 2 of registration: confirm OTP -> create user
require_once __DIR__ . '/../includes/functions.php';
csrf_check();

$email = strtolower(field('email'));
$code  = field('otp');

$st = db()->prepare('SELECT * FROM otps WHERE email = ? AND purpose = ? ORDER BY id DESC LIMIT 1');
$st->execute([$email, 'register']);
$row = $st->fetch();

if (!$row)                               json_err('Permintaan tidak ditemukan. Daftar ulang.');
if (strtotime($row['expires_at']) < time()) json_err('OTP kadaluarsa. Daftar ulang.');
if (!hash_equals($row['code'], $code))   json_err('Kode OTP salah.');

$data = json_decode($row['payload'], true) ?: [];
if (empty($data['name']) || empty($data['password_hash'])) json_err('Data pendaftaran rusak.');

// double-check email not taken
$c = db()->prepare('SELECT id FROM users WHERE email = ?');
$c->execute([$email]);
if ($c->fetch()) json_err('Email sudah terdaftar.');

$ins = db()->prepare('INSERT INTO users (name,email,password_hash,role,stars) VALUES (?,?,?,?,0)');
$ins->execute([$data['name'], $email, $data['password_hash'], 'user']);
$uid = (int)db()->lastInsertId();

db()->prepare('DELETE FROM otps WHERE email = ? AND purpose = ?')->execute([$email, 'register']);

$_SESSION['uid'] = $uid;
json_ok(['redirect' => 'dashboard.php'], 'Pendaftaran berhasil! Selamat datang.');
