<?php
// Step 1 of registration: validate + send OTP to email
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/mailer.php';
csrf_check();

$name  = field('name');
$email = strtolower(field('email'));
$pass  = field('password');

if ($name === '' || mb_strlen($name) < 2)         json_err('Nama tidak valid.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL))   json_err('Email tidak valid.');
if (strlen($pass) < 6)                            json_err('Password minimal 6 karakter.');

$st = db()->prepare('SELECT id FROM users WHERE email = ?');
$st->execute([$email]);
if ($st->fetch()) json_err('Email sudah terdaftar. Silakan login.');

$otp = gen_otp();
$payload = json_encode(['name' => $name, 'password_hash' => password_hash($pass, PASSWORD_DEFAULT)]);

// remove old pending for this email+purpose
db()->prepare('DELETE FROM otps WHERE email = ? AND purpose = ?')->execute([$email, 'register']);
$ins = db()->prepare('INSERT INTO otps (email,code,purpose,payload,expires_at) VALUES (?,?,?,?,?)');
$ins->execute([$email, $otp, 'register', $payload,
    date('Y-m-d H:i:s', time() + OTP_EXPIRY_MIN * 60)]);

$sent = send_otp_email($email, $otp, 'register');
$resp = ['email' => $email];
if (!$sent && APP_DEBUG) $resp['debug_otp'] = $otp; // shown only in debug if SMTP not set
json_ok($resp, $sent
    ? 'Kode OTP telah dikirim ke email Anda.'
    : 'OTP dibuat. (SMTP belum dikonfigurasi — cek pengaturan email.)');
