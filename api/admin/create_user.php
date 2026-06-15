<?php
// Admin/staff creates a new account (user or staff) from the dashboard.
require_once __DIR__ . '/../../includes/functions.php';
csrf_check();
$me = require_admin();

$name  = field('name');
$email = strtolower(field('email'));
$pass  = field('password');
$role  = field('role', 'user');

if (mb_strlen($name) < 2)                       json_err('Nama tidak valid.');
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) json_err('Email tidak valid.');
if (strlen($pass) < 6)                          json_err('Password minimal 6 karakter.');
if (!in_array($role, ['user', 'staff'], true))  json_err('Role tidak valid.');

// Only a full admin may create staff accounts.
if ($role === 'staff' && $me['role'] !== 'admin') {
    json_err('Hanya admin yang dapat membuat akun staff.', 403);
}

$st = db()->prepare('SELECT id FROM users WHERE email = ?');
$st->execute([$email]);
if ($st->fetch()) json_err('Email sudah terdaftar.');

$ins = db()->prepare('INSERT INTO users (name,email,password_hash,role,stars) VALUES (?,?,?,?,0)');
$ins->execute([$name, $email, password_hash($pass, PASSWORD_DEFAULT), $role]);

json_ok(['id' => (int)db()->lastInsertId(), 'role' => $role],
    ($role === 'staff' ? 'Akun staff' : 'Akun user') . ' berhasil dibuat.');
