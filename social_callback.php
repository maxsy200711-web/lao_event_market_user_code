<?php
session_start();
require_once __DIR__ . "/config.php";

// ຕົວຢ່າງຂໍ້ມູນທີ່ໄດ້ຮັບຈາກ Facebook / TikTok OAuth API Callback
$provider    = $_GET['provider'] ?? 'facebook'; // facebook, tiktok, google
$provider_id = $_GET['social_id'] ?? ''; 
$name        = $_GET['name'] ?? 'Organizer User';
$email       = $_GET['email'] ?? '';
$avatar      = $_GET['avatar'] ?? '';

if (empty($provider_id)) {
    header("Location: login.php?error=auth_failed");
    exit;
}

// 1. ກວດສອບວ່າ Organizer ຄົນນີ້ເຄີຍ Login ຜ່ານ Provider ນີ້ແລ້ວບໍ
$stmt = $pdo->prepare("SELECT * FROM organizers WHERE provider = ? AND provider_id = ? LIMIT 1");
$stmt->execute([$provider, $provider_id]);
$org = $stmt->fetch();

if (!$org) {
    // 2. ຖ້າເປັນຄັ້ງທຳອິດ -> ສ້າງ account auto (Auto Register)
    $stmt = $pdo->prepare("INSERT INTO organizers (organizer_name, email, provider, provider_id, avatar, status) VALUES (?, ?, ?, ?, ?, 'approved')");
    $stmt->execute([$name, $email, $provider, $provider_id, $avatar]);
    $organizer_id = $pdo->lastInsertId();
} else {
    $organizer_id = $org['organizer_id'];
    $name         = $org['organizer_name'];
}

// 3. ເປີດ Session ເຂົ້າໃຊ້ງານ Dashboard ໄດ້ເລີຍ
session_regenerate_id(true);
$_SESSION['organizer_id']   = $organizer_id;
$_SESSION['organizer_name'] = $name;
$_SESSION['auth_provider']  = $provider;

header("Location: organizer_dashboard.php");
exit;