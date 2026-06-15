<?php
require_once __DIR__ . '/../../includes/functions.php';
require_admin();

$q = trim($_GET['q'] ?? '');
$sql = 'SELECT id,name,email,role,avatar,stars,created_at FROM users';
$params = [];
if ($q !== '') {
    $sql .= ' WHERE name LIKE ? OR email LIKE ?';
    $params = ["%$q%", "%$q%"];
}
$sql .= ' ORDER BY id DESC';
$st = db()->prepare($sql);
$st->execute($params);
json_ok(['users' => $st->fetchAll()]);
