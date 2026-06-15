<?php
// Admin: update an ad's star reward, title, duration, or video URL.
require_once __DIR__ . '/../../includes/functions.php';
csrf_check();
require_admin();

$id = (int)field('id');
$st = db()->prepare('SELECT * FROM ads WHERE id = ?');
$st->execute([$id]);
$ad = $st->fetch();
if (!$ad) json_err('Iklan tidak ditemukan.');

$b = body();
$title  = isset($b['title'])       ? trim((string)$b['title'])       : $ad['title'];
$video  = isset($b['video_url'])   ? trim((string)$b['video_url'])   : $ad['video_url'];
$dur    = isset($b['duration'])    ? max(5, min(120, (int)$b['duration'])) : (int)$ad['duration'];
$star   = isset($b['star_reward']) ? max(0, (int)$b['star_reward'])  : (int)$ad['star_reward'];

if ($title === '') json_err('Judul tidak boleh kosong.');

db()->prepare('UPDATE ads SET title=?, video_url=?, duration=?, star_reward=? WHERE id=?')
    ->execute([$title, $video, $dur, $star, $id]);
json_ok([
    'ad' => ['id'=>$id,'title'=>$title,'video_url'=>$video,'duration'=>$dur,'star_reward'=>$star]
], 'Iklan diperbarui.');
