<?php
require_once __DIR__ . '/includes/functions.php';
$u = current_user();
if ($u) {
    header('Location: ' . ($u['role'] === 'admin' ? 'admin/index.php' : 'dashboard.php'));
    exit;
}
header('Location: login.php');
exit;
