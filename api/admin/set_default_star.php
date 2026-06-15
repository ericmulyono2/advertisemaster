<?php
// Admin: set the global default star reward; optionally apply to all ads.
require_once __DIR__ . '/../../includes/functions.php';
csrf_check();
require_admin();

$val = max(0, (int)field('default_star'));
$applyAll = (int)field('apply_all') === 1;

set_setting('default_star_reward', $val);
if ($applyAll) {
    db()->prepare('UPDATE ads SET star_reward = ?')->execute([$val]);
}
json_ok(['default_star' => $val], $applyAll
    ? "Default STAR = $val diterapkan ke semua iklan."
    : "Default STAR = $val disimpan.");
