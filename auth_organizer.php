<?php
// ໃສ່ໄຟລ໌ນີ້ໄວ້ເທິງສຸດຂອງທຸກໜ້າທີ່ Organizer ເທົ່ານັ້ນເຂົ້າໄດ້
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['organizer_id'])) {
    header("Location: login.php");
    exit;
}

require_once __DIR__ . '/config.php';
$approvalStmt = $pdo->prepare('SELECT status, is_verified FROM organizers WHERE organizer_id = ? LIMIT 1');
$approvalStmt->execute([(int)$_SESSION['organizer_id']]);
$approval = $approvalStmt->fetch();
if (!$approval || strtolower($approval['status'] ?? '') !== 'approved' || (int)$approval['is_verified'] !== 1) {
    unset($_SESSION['organizer_id'], $_SESSION['organizer_name'], $_SESSION['company_name'], $_SESSION['organizer_email']);
    header('Location: login.php?error=' . ($approval && strtolower($approval['status'] ?? '') === 'rejected' ? 'organizer_rejected' : 'organizer_pending'));
    exit;
}

// ຕົວແປສະດວກໃຊ້ໃນທຸກໜ້າ
$organizer_id   = (int) $_SESSION['organizer_id'];
$organizer_name = $_SESSION['organizer_name'] ?? '';
$company_name   = $_SESSION['company_name'] ?? '';
