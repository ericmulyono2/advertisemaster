<?php
// Admin: list all ads (numbered) with on/off + star setting.
require_once __DIR__ . '/../../includes/functions.php';
require_admin();

$rows = db()->query('SELECT id,ad_number,title,video_url,duration,star_reward,is_active
    FROM ads ORDER BY ad_number ASC')->fetchAll();
json_ok(['ads' => $rows, 'default_star' => (int)get_setting('default_star_reward', DEFAULT_STAR_REWARD)]);
