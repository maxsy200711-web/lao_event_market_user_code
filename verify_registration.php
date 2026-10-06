<?php
session_start();
$pending = $_SESSION['registration_pending'] ?? null;
if (!$pending) {
    header('Location: register.php');
    exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $otp = trim($_POST['otp'] ?? '');
    if (time() > $pending['otp_expires'] || !password_verify($otp, $pending['otp_hash'])) {
        $error = 'Invalid or expired OTP.';
    } else {
        $_SESSION['registration_verified'] = true;
        header('Location: set_password.php');
        exit;
    }
}
?>
<!doctype html>
<html lang="lo">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify email - LAOeventMarket</title>
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>
<main class="auth-card">
    <a class="auth-brand" href="index.php" aria-label="LAOeventMarket home">
        <span class="brand-mark" aria-hidden="true">⌂</span>
        <span><b>LAO</b>event<b>Market</b></span>
    </a>
    <p class="auth-title">Verify your email</p>
    <p class="auth-switch" style="margin:0 0 18px">We sent a 6-digit OTP to <?= htmlspecialchars($pending['email'], ENT_QUOTES, 'UTF-8') ?>.</p>
    <?php if ($error): ?><p class="auth-message auth-error"><?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?></p><?php endif; ?>
    <form class="auth-form" method="post">
        <label for="otp">Verification code</label>
        <input class="otp-input" id="otp" name="otp" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" required autofocus>
        <button class="auth-submit" type="submit">Confirm email</button>
    </form>
    <p class="auth-switch">Wrong email? <a href="register.php">Back to register</a></p>
    <div class="auth-home"><a href="index.php">Back to home</a></div>
</main>
<script src="assets/site-i18n.js"></script>
    </body>
</html>
