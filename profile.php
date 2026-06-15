<?php
require_once __DIR__ . '/includes/functions.php';
$u = current_user();
if (!$u) { header('Location: login.php'); exit; }
$BASE = ''; $PAGE_TITLE = 'Profil'; $ACTIVE = 'profile';
include __DIR__ . '/partials/header.php';
include __DIR__ . '/partials/profile_body.php';
include __DIR__ . '/partials/footer.php';
?>
<script src="assets/js/profile.js" defer></script>
