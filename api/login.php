<?php
require_once __DIR__ . '/../includes/functions.php';
csrf_check();

$email = strtolower(field('email'));
$pass  = field('password');

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_err('Email tidak valid.');
if ($pass === '') json_err('Password wajib diisi.');

$st = db()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
$st->execute([$email]);
$u = $st->fetch();

if (!$u || !password_verify($pass, $u['password_hash'])) {
    json_err('Email atau password salah.', 401);
}

$_SESSION['uid'] = (int)$u['id'];
session_regenerate_id(true);
$_SESSION['uid'] = (int)$u['id'];

$redirect = is_panel_role($u['role']) ? 'admin/index.php' : 'dashboard.php';
json_ok(['redirect' => $redirect, 'role' => $u['role']], 'Login berhasil.');
