<?php
$errorCode = $_GET['error'] ?? '';
$error = $errorCode !== '';
$registered = isset($_GET['registered']);
$registeredOrganizer = ($_GET['role'] ?? '') === 'organizer';
?>
<!doctype html>
<html lang="lo">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - LAOeventMarket</title>
    <link rel="stylesheet" href="assets/auth.css">
</head>
<body>
<main class="auth-card">
    <a class="auth-brand" href="index.php" aria-label="LAOeventMarket home">
        <?php include __DIR__ . '/site_logo.php'; ?>
    </a>
    <p class="auth-title">Sign in to your account</p>
    <?php if ($errorCode === 'organizer_pending'): ?><p class="auth-message auth-error">Your Organizer account is waiting for Admin approval.</p>
    <?php elseif ($errorCode === 'organizer_rejected'): ?><p class="auth-message auth-error">Your Organizer registration was rejected. Please contact the administrator.</p>
    <?php elseif ($error): ?><p class="auth-message auth-error">Username/email or password is incorrect.</p><?php endif; ?>
    <?php if ($registered && $registeredOrganizer): ?><p class="auth-message auth-ok">Registration complete. Your Organizer account is waiting for Admin approval.</p>
    <?php elseif ($registered): ?><p class="auth-message auth-ok">Registration complete. Please sign in.</p><?php endif; ?>
    <form class="auth-form" action="login_process.php" method="post">
        <label for="username">Username or email</label>
        <input id="username" name="username" required autofocus autocomplete="username">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required autocomplete="current-password">
        <button class="auth-submit" type="submit">Login</button>
    </form>
    <p class="auth-switch">Don't have an account? <a href="register.php">Register</a></p>
    <div class="auth-home"><a href="index.php">Back to home</a></div>
</main>
<script src="assets/site-i18n.js"></script>
    </body>
</html>
