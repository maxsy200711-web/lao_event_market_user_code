<?php
session_start();
require_once __DIR__ . "/config.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register.php");
    exit;
}

$role             = trim($_POST['role'] ?? 'user');
$fullname         = trim($_POST['fullname'] ?? '');
$email            = trim($_POST['email'] ?? '');
$phone            = trim($_POST['phone'] ?? '');
$password         = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// ---- 1. ກວດຄວາມຖືກຕ້ອງເບື້ອງຕົ້ນ ----
if ($fullname === '' || $email === '' || $phone === '' || $password === '') {
    header("Location: register.php?error=empty_fields&role=$role");
    exit;
}

if (!preg_match('/^[0-9+ ]{8,20}$/', $phone)) {
    header("Location: register.php?error=phone_invalid&role=$role");
    exit;
}

if ($password !== $confirm_password) {
    header("Location: register.php?error=password_mismatch&role=$role");
    exit;
}

if (strlen($password) < 6) {
    header("Location: register.php?error=password_short&role=$role");
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$table = $role === 'organizer' ? 'organizers' : 'users';

// ---- 2. ກວດອີເມວ ຫຼື ເບີໂທ ຊ້ຳ ----
$stmt = $pdo->prepare("SELECT email, phone FROM `$table` WHERE email = ? OR phone = ? LIMIT 1");
$stmt->execute([$email, $phone]);
$dupe = $stmt->fetch();
if ($dupe) {
    $error = ($dupe['email'] === $email) ? 'email_exists' : 'phone_exists';
    header("Location: register.php?error=$error&role=$role");
    exit;
}

if ($role === 'organizer') {
    // -------------------------------------------------------------
    //  ຈັດການການລົງທະບຽນສໍາລັບ ຜູ້ຈັດງານ (Organizer)
    // -------------------------------------------------------------
    $stmt = $pdo->prepare("INSERT INTO organizers (organizer_name, email, password, phone) VALUES (?, ?, ?, ?)");
    $inserted = $stmt->execute([$fullname, $email, $hashed_password, $phone]);

    if ($inserted) {
        $organizer_id = $pdo->lastInsertId();

        session_regenerate_id(true);
        $_SESSION['organizer_id']   = $organizer_id;
        $_SESSION['organizer_name'] = $fullname;
        $_SESSION['company_name']   = ''; // ຕັ້ງເປັນຄ່າຫວ່າງໄວ້ກ່ອນ ເພື່ອໃຫ້ແກ້ໄຂໃນ Profile ພາຍຫຼັງ

        header("Location: organizer_dashboard.php");
        exit;
    } else {
        header("Location: register.php?error=system_error&role=$role");
        exit;
    }

} else {
    // -------------------------------------------------------------
    //  ຈັດການການລົງທະບຽນສໍາລັບ ຜູ້ໃຊ້ທົ່ວໄປ (General User)
    // -------------------------------------------------------------
    $stmt = $pdo->prepare("INSERT INTO users (full_name, email, password, phone) VALUES (?, ?, ?, ?)");
    $inserted = $stmt->execute([$fullname, $email, $hashed_password, $phone]);

    if ($inserted) {
        $user_id = $pdo->lastInsertId();

        session_regenerate_id(true);
        $_SESSION['user_id']   = $user_id;
        $_SESSION['full_name'] = $fullname;

        header("Location: index.php");
        exit;
    } else {
        header("Location: register.php?error=system_error&role=$role");
        exit;
    }
}