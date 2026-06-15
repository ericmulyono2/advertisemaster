<?php
// Admin: turn an ad on/off.
require_once __DIR__ . '/../../includes/functions.php';
csrf_check();
require_admin();

$id = (int)field('id');
$active = (int)field('is_active') === 1 ? 1 : 0;

$st = db()->prepare('SELECT id FROM ads WHERE id = ?');
$st->execute([$id]);
if (!$st->fetch()) json_err('Iklan tidak ditemukan.');

db()->prepare('UPDATE ads SET is_active = ? WHERE id = ?')->execute([$active, $id]);
json_ok(['is_active' => $active], $active ? 'Iklan diaktifkan.' : 'Iklan dinonaktifkan.');
