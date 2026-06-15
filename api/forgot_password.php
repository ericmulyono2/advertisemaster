<?php
// Step 1 forgot password: send OTP if email exists
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/mailer.php';
csrf_check();

$email = strtolower(field('email'));
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_err('Email tidak valid.');

$st = db()->prepare('SELECT id FROM users WHERE email = ?');
$st->execute([$email]);
$exists = (bool)$st->fetch();

// Always respond success-ish to avoid email enumeration, but only send if exists
if ($exists) {
    $otp = gen_otp();
    db()->prepare('DELETE FROM otps WHERE email = ? AND purpose = ?')->execute([$email, 'reset']);
    db()->prepare('INSERT INTO otps (email,code,purpose,expires_at) VALUES (?,?,?,?)')
        ->execute([$email, $otp, 'reset', date('Y-m-d H:i:s', time() + OTP_EXPIRY_MIN * 60)]);
    $sent = send_otp_email($email, $otp, 'reset');
    $resp = ['email' => $email];
    if (!$sent && APP_DEBUG) $resp['debug_otp'] = $otp;
    json_ok($resp, 'Jika email terdaftar, kode OTP telah dikirim.');
}
json_ok(['email' => $email], 'Jika email terdaftar, kode OTP telah dikirim.');
