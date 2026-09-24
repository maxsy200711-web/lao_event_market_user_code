<?php
session_start();
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/smtp_mailer.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: register.php'); exit; }

$username = trim($_POST['username'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone = trim($_POST['phone'] ?? '');
$role = strtolower(trim($_POST['role'] ?? 'user'));
$organizerName = trim($_POST['organizer_name'] ?? '');
$companyName = trim($_POST['company_name'] ?? '');

if (!in_array($role, ['user', 'organizer'], true) || $username === '' || !$email || !preg_match('/^[0-9+ ]{8,20}$/', $phone)) {
    header('Location: register.php?error=invalid&role=' . urlencode($role)); exit;
}
if ($role === 'organizer' && ($organizerName === '' || $companyName === '')) {
    header('Location: register.php?error=invalid&role=organizer'); exit;
}

$stmt = $pdo->prepare('SELECT 1 FROM users WHERE (username = ? OR email = ? OR phone = ?) LIMIT 1');
$stmt->execute([$username, $email, $phone]);
if ($stmt->fetchColumn()) { header('Location: register.php?error=exists&role=' . urlencode($role)); exit; }

if ($role === 'organizer') {
    $stmt = $pdo->prepare('SELECT 1 FROM organizers WHERE email = ? OR phone = ? LIMIT 1');
    $stmt->execute([$email, $phone]);
    if ($stmt->fetchColumn()) { header('Location: register.php?error=organizer_exists&role=organizer'); exit; }
}

$otp = (string)random_int(100000, 999999);
$_SESSION['registration_pending'] = [
        'username' => $username, 'email' => $email, 'phone' => $phone,
        'role' => $role, 'organizer_name' => $organizerName, 'company_name' => $companyName,
    'otp_hash' => password_hash($otp, PASSWORD_DEFAULT),
    'otp_expires' => time() + 600,
];

if (!smtp_send_otp($email, $otp)) {
    unset($_SESSION['registration_pending']);
    header('Location: register.php?error=mail'); exit;
}
header('Location: verify_registration.php');
