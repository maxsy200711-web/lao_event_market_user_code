<?php
session_start();
date_default_timezone_set('Asia/Vientiane'); // ຕັ້ງເຂດເວລາໃຫ້ເປັນເວລາລາວ (+7)
require_once __DIR__ . "/config.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $role = $_POST['role'] ?? 'user';

    if (!$email) {
        die("ກະລຸນາປ້ອນ ອີເມວ ໃຫ້ຖືກຕ້ອງ");
    }

    $otp = sprintf("%06d", mt_rand(1, 999999));
    $expires_at = date("Y-m-d H:i:s", strtotime("+60 seconds"));

    $table = ($role === 'organizer') ? 'organizers' : 'users';

    $stmt = $pdo->prepare("SELECT email FROM {$table} WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        $update = $pdo->prepare("UPDATE {$table} SET otp_code = ?, otp_expires_at = ? WHERE email = ?");
        $update->execute([$otp, $expires_at, $email]);
    } else {
        if ($role === 'organizer') {
            $insert = $pdo->prepare("INSERT INTO organizers (email, otp_code, otp_expires_at, status) VALUES (?, ?, ?, 'approved')");
            $insert->execute([$email, $otp, $expires_at]);
        } else {
            $insert = $pdo->prepare("INSERT INTO users (email, otp_code, otp_expires_at, is_verified) VALUES (?, ?, ?, 0)");
            $insert->execute([$email, $otp, $expires_at]);
        }
    }

    $_SESSION['auth_email'] = $email;
    $_SESSION['auth_role']  = $role;
    $_SESSION['demo_otp']   = $otp;

    header("Location: verify_otp.php");
    exit;
} else {
    header("Location: login.php");
    exit;
}