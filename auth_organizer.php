<?php
// ໃສ່ໄຟລ໌ນີ້ໄວ້ເທິງສຸດຂອງທຸກໜ້າທີ່ Organizer ເທົ່ານັ້ນເຂົ້າໄດ້
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['organizer_id'])) {
    header("Location: login.php");
    exit;
}

// ຕົວແປສະດວກໃຊ້ໃນທຸກໜ້າ
$organizer_id   = (int) $_SESSION['organizer_id'];
$organizer_name = $_SESSION['organizer_name'] ?? '';
$company_name   = $_SESSION['company_name'] ?? '';
