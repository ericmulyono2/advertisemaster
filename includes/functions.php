<?php
require_once __DIR__ . '/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* ---------- JSON response helpers ---------- */
function json_out($data, int $code = 200): void {
    http_response_code($code);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}
function json_ok($data = [], string $msg = 'OK'): void {
    json_out(array_merge(['success' => true, 'message' => $msg], $data));
}
function json_err(string $msg, int $code = 400): void {
    json_out(['success' => false, 'message' => $msg], $code);
}

/* ---------- input ---------- */
function body(): array {
    $raw = file_get_contents('php://input');
    $json = json_decode($raw, true);
    if (is_array($json)) return $json;
    return $_POST;
}
function field(string $key, $default = ''): string {
    $b = body();
    return isset($b[$key]) ? trim((string)$b[$key]) : $default;
}

/* ---------- CSRF ---------- */
function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_check(): void {
    $b = body();
    $t = $b['csrf'] ?? ($_SERVER['HTTP_X_CSRF'] ?? '');
    if (!hash_equals($_SESSION['csrf'] ?? '', (string)$t)) {
        json_err('Sesi tidak valid (CSRF). Muat ulang halaman.', 419);
    }
}

/* ---------- auth state ---------- */
function current_user(): ?array {
    if (empty($_SESSION['uid'])) return null;
    static $u = null;
    if ($u === null) {
        $st = db()->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $st->execute([$_SESSION['uid']]);
        $u = $st->fetch() ?: null;
    }
    return $u;
}
function require_login(): array {
    $u = current_user();
    if (!$u) json_err('Silakan login terlebih dahulu.', 401);
    return $u;
}
/** Panel access: admin OR staff (staff = pekerja di bawah admin, dashboard sama). */
function require_admin(): array {
    $u = require_login();
    if (!in_array($u['role'] ?? '', ['admin', 'staff'], true)) {
        json_err('Akses admin/staff diperlukan.', 403);
    }
    return $u;
}
/** Strict admin-only (mis. membuat akun staff). */
function require_super_admin(): array {
    $u = require_login();
    if (($u['role'] ?? '') !== 'admin') json_err('Hanya admin yang dapat melakukan ini.', 403);
    return $u;
}
function is_panel_role(?string $r): bool { return in_array($r, ['admin', 'staff'], true); }

/* ---------- OTP ---------- */
function gen_otp(): string {
    return str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
}

/* ---------- settings ---------- */
function get_setting(string $key, $default = null) {
    $st = db()->prepare('SELECT v FROM settings WHERE k = ? LIMIT 1');
    $st->execute([$key]);
    $r = $st->fetch();
    return $r ? $r['v'] : $default;
}
function set_setting(string $key, $value): void {
    $st = db()->prepare('INSERT INTO settings (k, v) VALUES (?, ?) ON DUPLICATE KEY UPDATE v = VALUES(v)');
    $st->execute([$key, (string)$value]);
}

function e(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
