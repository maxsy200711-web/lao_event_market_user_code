<?php
session_start();
require_once __DIR__ . "/config.php";

$email    = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
// ໝາຍເຫດ: ຕາຕະລາງ admins ປັດຈຸບັນຍັງບໍ່ມີຄໍລໍາເກັບ 2FA secret,
// ຈຶ່ງຍັງບໍ່ໄດ້ກວດ code 2FA ຢ່າງແທ້ຈິງ - ຊ່ອງນີ້ໄວ້ຮອງຮັບການເພີ່ມ TOTP ພາຍຫຼັງ.

if ($email === '' || $password === '') {
    header("Location: admin_login.php?error=1");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM admins WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$admin = $stmt->fetch();

if (!$admin || $password !== $admin['password']) {
    header("Location: admin_login.php?error=1");
    exit;
}

session_regenerate_id(true);
$_SESSION['admin_id']   = $admin['admin_id'];
$_SESSION['admin_name'] = $admin['full_name'];
$_SESSION['admin_role'] = $admin['role']; // 'superadmin' ຫຼື 'admin'

header("Location: admin_dashboard.php");
exit;
