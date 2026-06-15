<?php
require_once __DIR__ . '/../config/config.php';

/**
 * Minimal self-contained SMTP mailer (no Composer needed).
 * Supports STARTTLS (port 587) and implicit SSL (port 465) with AUTH LOGIN.
 * Falls back to PHP mail() if SMTP is not configured.
 */

function send_otp_email(string $to, string $otp, string $purpose = 'register'): bool {
    $subjectMap = [
        'register' => APP_NAME . ' — Kode Verifikasi Pendaftaran',
        'reset'    => APP_NAME . ' — Kode Reset Password',
    ];
    $subject = $subjectMap[$purpose] ?? (APP_NAME . ' — Kode OTP');
    $headline = $purpose === 'reset' ? 'Reset Password' : 'Verifikasi Email';

    $html = '
    <div style="font-family:Segoe UI,Arial,sans-serif;background:#0a0e1a;padding:32px;border-radius:16px;color:#e6ecff;max-width:480px;margin:auto">
      <h1 style="margin:0 0 4px;font-size:22px;background:linear-gradient(90deg,#6a5cff,#22d3ee);-webkit-background-clip:text;background-clip:text;color:transparent">AdvertiseMaster</h1>
      <p style="color:#8b96b8;margin:0 0 24px;font-size:13px">' . htmlspecialchars($headline) . '</p>
      <p style="font-size:14px">Gunakan kode OTP berikut. Berlaku ' . OTP_EXPIRY_MIN . ' menit:</p>
      <div style="font-size:36px;font-weight:800;letter-spacing:10px;text-align:center;background:#131a30;border:1px solid #2a3358;border-radius:12px;padding:20px;margin:18px 0;color:#22d3ee">'
      . htmlspecialchars($otp) . '</div>
      <p style="font-size:12px;color:#8b96b8">Jika Anda tidak meminta ini, abaikan email ini.</p>
    </div>';

    return send_mail($to, $subject, $html);
}

function send_mail(string $to, string $subject, string $htmlBody): bool {
    // No SMTP configured -> try native mail()
    if (SMTP_HOST === '' || SMTP_USER === '') {
        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
        $headers .= 'From: ' . SMTP_FROM_NAME . ' <no-reply@' . ($_SERVER['HTTP_HOST'] ?? 'localhost') . ">\r\n";
        return @mail($to, $subject, $htmlBody, $headers);
    }

    $secure = SMTP_SECURE;
    $host   = ($secure === 'ssl' ? 'ssl://' : '') . SMTP_HOST;
    $errno = 0; $errstr = '';
    $fp = @fsockopen($host, SMTP_PORT, $errno, $errstr, 15);
    if (!$fp) { error_log("SMTP connect failed: $errstr ($errno)"); return false; }
    stream_set_timeout($fp, 15);

    $read = function() use ($fp) {
        $data = '';
        while ($line = fgets($fp, 515)) {
            $data .= $line;
            if (isset($line[3]) && $line[3] === ' ') break;
        }
        return $data;
    };
    $cmd = function($c) use ($fp, $read) { fwrite($fp, $c . "\r\n"); return $read(); };

    $read(); // greeting
    $ehlo = "EHLO " . (SMTP_HOST);
    $cmd($ehlo);

    if ($secure === 'tls') {
        $cmd('STARTTLS');
        if (!stream_socket_enable_crypto($fp, true, STREAM_CRYPTO_METHOD_TLS_CLIENT
            | STREAM_CRYPTO_METHOD_TLSv1_1_CLIENT | STREAM_CRYPTO_METHOD_TLSv1_2_CLIENT)) {
            fclose($fp); error_log('SMTP STARTTLS failed'); return false;
        }
        $cmd($ehlo);
    }

    $cmd('AUTH LOGIN');
    $cmd(base64_encode(SMTP_USER));
    $authResp = $cmd(base64_encode(SMTP_PASS));
    if (strpos($authResp, '235') === false) {
        fclose($fp); error_log('SMTP auth failed: ' . trim($authResp)); return false;
    }

    $from = SMTP_USER;
    $cmd('MAIL FROM:<' . $from . '>');
    $rcpt = $cmd('RCPT TO:<' . $to . '>');
    if (strpos($rcpt, '250') === false) { fclose($fp); return false; }
    $cmd('DATA');

    $headers  = 'From: ' . SMTP_FROM_NAME . ' <' . $from . ">\r\n";
    $headers .= 'To: <' . $to . ">\r\n";
    $headers .= 'Subject: =?UTF-8?B?' . base64_encode($subject) . "?=\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "Content-Transfer-Encoding: base64\r\n";
    $data = $headers . "\r\n" . chunk_split(base64_encode($htmlBody));
    $sendResp = $cmd($data . "\r\n.");
    $cmd('QUIT');
    fclose($fp);

    return strpos($sendResp, '250') !== false;
}
