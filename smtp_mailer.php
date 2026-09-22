<?php
function smtp_send_otp(string $to, string $otp): bool
{
    $host = getenv('LAO_SMTP_HOST') ?: 'smtp.gmail.com';
    $port = (int)(getenv('LAO_SMTP_PORT') ?: 587);
    $user = getenv('LAO_SMTP_USER') ?: 'sengtest7@gmail.com';
    $pass = str_replace(' ', '', getenv('LAO_SMTP_PASSWORD') ?: 'toec fkqx kaok jhrv');

    $socket = @stream_socket_client("tcp://{$host}:{$port}", $errno, $errstr, 15);
    if (!$socket) return false;
    stream_set_timeout($socket, 15);

    $expect = static function ($socket, int $code): bool {
        $response = '';
        while (($line = fgets($socket, 515)) !== false) {
            $response .= $line;
            if (strlen($line) < 4 || $line[3] !== '-') break;
        }
        return str_starts_with($response, (string)$code);
    };
    $command = static function ($socket, string $command, int $code) use ($expect): bool {
        fwrite($socket, $command . "\r\n");
        return $expect($socket, $code);
    };

    if (!$expect($socket, 220) || !$command($socket, 'EHLO localhost', 250)) return false;
    if (!$command($socket, 'STARTTLS', 220) || !stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) return false;
    if (!$command($socket, 'EHLO localhost', 250) || !$command($socket, 'AUTH LOGIN', 334)) return false;
    if (!$command($socket, base64_encode($user), 334) || !$command($socket, base64_encode($pass), 235)) return false;
    if (!$command($socket, "MAIL FROM:<{$user}>", 250) || !$command($socket, "RCPT TO:<{$to}>", 250)) return false;

    fwrite($socket, "DATA\r\n");
    if (!$expect($socket, 354)) return false;
    $subject = 'LAOeventMarket email verification code';
    $body = "Your LAOeventMarket verification code is: {$otp}\r\n\r\nThis code expires in 10 minutes.";
    $headers = "From: LAOeventMarket <{$user}>\r\n" .
        "To: <{$to}>\r\n" .
        "Subject: {$subject}\r\n" .
        "Content-Type: text/plain; charset=UTF-8\r\n";
    fwrite($socket, $headers . "\r\n" . $body . "\r\n.\r\n");
    $ok = $expect($socket, 250);
    fwrite($socket, "QUIT\r\n");
    fclose($socket);
    return $ok;
}
