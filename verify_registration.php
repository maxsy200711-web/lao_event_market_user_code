<?php
session_start();
$pending = $_SESSION['registration_pending'] ?? null;
if (!$pending) { header('Location: register.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');
    if (time() > $pending['otp_expires'] || !password_verify($otp, $pending['otp_hash'])) {
        $error = 'Invalid or expired OTP.';
    } else {
        $_SESSION['registration_verified'] = true;
        header('Location: set_password.php'); exit;
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Verify email</title><style>body{font-family:Arial;display:grid;place-items:center;min-height:100vh;background:#0f172a}.card{background:#fff;padding:30px;border-radius:14px;width:min(390px,calc(100% - 40px))}input,button{width:100%;padding:12px;margin:7px 0;box-sizing:border-box}input{text-align:center;letter-spacing:5px}button{background:#10b981;color:#fff;border:0;border-radius:7px;font-weight:bold}.error{color:#b91c1c}</style></head><body><div class="card"><h2>Verify your email</h2><p>We sent a 6-digit OTP to <?= htmlspecialchars($pending['email']) ?>.</p><?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?><form method="post"><input name="otp" inputmode="numeric" maxlength="6" required autofocus><button>Confirm email</button></form></div></body></html>
