<?php
require_once __DIR__ . '/../includes/functions.php';
csrf_check();
$u = require_login();

$name = field('name');
if (mb_strlen($name) < 2) json_err('Nama tidak valid.');

db()->prepare('UPDATE users SET name = ? WHERE id = ?')->execute([$name, $u['id']]);
json_ok(['name' => $name], 'Profil diperbarui.');
