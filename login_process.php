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
    $_SESSION['role'] = 'admin';
    header('Location: admin_dashboard.php'); exit;
}

// 2) Find the account in users first. The role column is the source of truth
// for accounts that were promoted through the organizer request workflow.
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
    $_SESSION['role'] = 'admin';
    header('Location: admin_dashboard.php'); exit;
}

if ($user && !empty($user['password']) && password_verify($password, $user['password']) && ($user['role_id'] ?? 'USER') === 'ORGANIZER') {
    $stmt = $pdo->prepare('SELECT o.* FROM organizer_users ou JOIN organizers o ON o.organizer_id = ou.organizer_id WHERE ou.user_id = ? ORDER BY ou.created_at ASC LIMIT 1');
    $stmt->execute([$user['user_id']]);
    $organizer = $stmt->fetch();
    if ($organizer) {
        session_regenerate_id(true);
        unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['full_name'], $_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
        $_SESSION['organizer_id'] = $organizer['organizer_id'];
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['organizer_name'] = $organizer['organizer_name'];
        $_SESSION['company_name'] = $organizer['company_name'] ?? '';
        $_SESSION['organizer_email'] = $organizer['email'];
        $_SESSION['role'] = 'organizer';
        header('Location: organizer_dashboard.php'); exit;
    }
}

// 3) Legacy organizer accounts that are not linked to users.user_id.
$stmt = $pdo->prepare('SELECT * FROM organizers WHERE email = ? OR organizer_name = ? LIMIT 1');
$stmt->execute([$identifier, $identifier]);
$organizer = $stmt->fetch();
if ($organizer && !empty($organizer['password']) && password_verify($password, $organizer['password'])) {
    session_regenerate_id(true);
    unset($_SESSION['user_id'], $_SESSION['user_email'], $_SESSION['full_name'], $_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
    $_SESSION['organizer_id'] = $organizer['organizer_id'];
    $_SESSION['organizer_name'] = $organizer['organizer_name'];
    $_SESSION['company_name'] = $organizer['company_name'] ?? '';
    $_SESSION['organizer_email'] = $organizer['email'];
    $_SESSION['role'] = 'organizer';
    header('Location: organizer_dashboard.php'); exit;
}

// 4) General user: full_name is the current username field.
if ($user && !empty($user['password']) && password_verify($password, $user['password'])) {
    session_regenerate_id(true);
    unset($_SESSION['organizer_id'], $_SESSION['organizer_email'], $_SESSION['organizer_name'], $_SESSION['company_name'], $_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_role']);
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['user_email'] = $user['email'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = 'user';
    header('Location: index.php'); exit;
}

header('Location: login.php?error=1'); exit;
