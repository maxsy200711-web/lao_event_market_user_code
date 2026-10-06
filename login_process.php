<?php
session_start(); require_once __DIR__ . '/config.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: login.php'); exit; }
$identifier = trim($_POST['username'] ?? '');
$password = $_POST['password'] ?? '';

if ($identifier === '' || $password === '') {
    header('Location: login.php?error=1'); exit;
}

// 1) Admin: admin login uses email and has its own admin_role.
$stmt = $pdo->prepare('SELECT * FROM admins WHERE email = ? LIMIT 1');
$stmt->execute([$identifier]);
$admin = $stmt->fetch();
$adminPasswordOk = $admin && !empty($admin['password']) &&
    (password_verify($password, $admin['password']) || hash_equals((string)$admin['password'], $password));

if ($adminPasswordOk) {
    session_regenerate_id(true);
    unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['full_name'], $_SESSION['organizer_id'], $_SESSION['organizer_email'], $_SESSION['organizer_name'], $_SESSION['company_name']);
    $_SESSION['admin_id'] = $admin['admin_id'];
    $_SESSION['admin_name'] = $admin['full_name'];
    $_SESSION['admin_role'] = $admin['role'] ?? 'admin';
    $_SESSION['admin_source'] = 'admins';
    $_SESSION['admin_email'] = $admin['email'];
    $_SESSION['role'] = 'admin';
    header('Location: admin_dashboard.php'); exit;
}

// 2) Organizer accounts and credentials are stored directly in organizers.
$stmt = $pdo->prepare('SELECT * FROM organizers WHERE username = ? OR email = ? LIMIT 1');
$stmt->execute([$identifier, $identifier]);
$organizer = $stmt->fetch();
if ($organizer && !empty($organizer['password']) && password_verify($password, $organizer['password'])) {
    if (strtolower($organizer['status'] ?? '') === 'approved' && (int)$organizer['is_verified'] === 1) {
        session_regenerate_id(true);
        unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['full_name'], $_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
        $_SESSION['organizer_id'] = (int)$organizer['organizer_id'];
        $_SESSION['organizer_name'] = $organizer['organizer_name'];
        $_SESSION['company_name'] = $organizer['company_name'] ?? '';
        $_SESSION['organizer_email'] = $organizer['email'];
        $_SESSION['organizer_username'] = $organizer['username'];
        $_SESSION['role'] = 'organizer';
        header('Location: organizer_dashboard.php'); exit;
    }
    $error = strtolower($organizer['status'] ?? '') === 'rejected' ? 'organizer_rejected' : 'organizer_pending';
    header('Location: login.php?error=' . $error); exit;
}

// Let applicants know their Organizer credentials are awaiting review without creating a user row.
$stmt = $pdo->prepare("SELECT password FROM organizer_requests WHERE status = 'pending' AND (username = ? OR email = ?) ORDER BY created_at DESC LIMIT 1");
$stmt->execute([$identifier, $identifier]);
$pendingRequest = $stmt->fetch();
if ($pendingRequest && !empty($pendingRequest['password']) && password_verify($password, $pendingRequest['password'])) {
    header('Location: login.php?error=organizer_pending'); exit;
}

// 3) Normal users and legacy admin accounts authenticate from users.
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ? OR username = ? OR full_name = ? LIMIT 1');
$stmt->execute([$identifier, $identifier, $identifier]);
$user = $stmt->fetch();

// Admin accounts can also live in the users table with role_id = ADMIN.
if ($user && !empty($user['password']) && password_verify($password, $user['password']) && ($user['role_id'] ?? 'USER') === 'ADMIN') {
    session_regenerate_id(true);
    unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['full_name'], $_SESSION['organizer_id'], $_SESSION['organizer_email'], $_SESSION['organizer_name'], $_SESSION['company_name']);
    $_SESSION['admin_id'] = $user['user_id'];
    $_SESSION['admin_name'] = $user['full_name'];
    $_SESSION['admin_role'] = 'admin';
    $_SESSION['admin_source'] = 'users';
    $_SESSION['admin_email'] = $user['email'];
    $_SESSION['role'] = 'admin';
    header('Location: admin_dashboard.php'); exit;
}

// 4) Only USER records may enter the normal-user area; legacy organizer rows stay inactive here.
if ($user && ($user['role_id'] ?? 'USER') === 'USER' && !empty($user['password']) && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    unset($_SESSION['organizer_id'], $_SESSION['organizer_email'], $_SESSION['organizer_name'], $_SESSION['company_name'], $_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = 'user';
    header('Location: index.php'); exit;
}

header('Location: login.php?error=1'); exit;
