<?php
session_start();
require_once __DIR__ . '/config.php';
$pending = $_SESSION['registration_pending'] ?? null;
if (
    !$pending ||
    empty($_SESSION['registration_verified']) ||
    !in_array($pending['role'] ?? '', ['user', 'organizer'], true) ||
    (($pending['role'] ?? '') === 'organizer' && (empty($pending['organizer_name']) || empty($pending['company_name'])))
) {
    header('Location: register.php');
    exit;
}
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    if (strlen($password) < 6) $error = 'Password must be at least 6 characters.';
    elseif ($password !== $confirm) $error = 'Passwords do not match.';
    else {
        try {
            $pdo->beginTransaction();
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            if ($pending['role'] === 'organizer') {
                // Organizer applications stay in the request queue until an admin approves them.
                $requestStmt = $pdo->prepare("INSERT INTO organizer_requests (username, email, password, organizer_name, company_name, phone, status) VALUES (?, ?, ?, ?, ?, ?, 'pending')");
                $requestStmt->execute([
                    $pending['username'], $pending['email'], $passwordHash,
                    $pending['organizer_name'], $pending['company_name'], $pending['phone'],
                ]);
            } else {
                $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, phone, password, is_verified, role_id) VALUES (?, ?, ?, ?, ?, 1, 'USER')");
                $stmt->execute([
                    $pending['username'], $pending['username'], $pending['email'],
                    $pending['phone'], $passwordHash,
                ]);
            }

            $pdo->commit();
            unset($_SESSION['registration_pending'], $_SESSION['registration_verified']);
            header('Location: login.php?registered=1'); exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'Could not create the account. Please try again.';
        }
    }
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Set password</title><style>body{font-family: Noto sans lao;display:grid;place-items:center;min-height:100vh;background:#0f172a}.card{background:white;padding:30px;border-radius:14px;width:min(390px,calc(100% - 40px))}input,button{width:100%;padding:12px;margin:7px 0;box-sizing:border-box}button{background:#10b981;color:#fff;border:0;border-radius:7px;font-weight:bold}.error{color:#b91c1c}</style></head><body><div class="card"><h2>Create your password</h2><p>Email verified for <?= htmlspecialchars($pending['email']) ?>.</p><?php if($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?><form method="post"><input type="password" name="password" placeholder="Password" minlength="6" required><input type="password" name="confirm_password" placeholder="Confirm password" minlength="6" required><button>Create account</button></form></div><script src="assets/site-i18n.js"></script>
    </body></html>
