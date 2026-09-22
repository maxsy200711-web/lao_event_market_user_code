<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit;
}

$admin_id   = (int) $_SESSION['admin_id'];
$admin_name = $_SESSION['admin_name'] ?? '';
$admin_role = $_SESSION['admin_role'] ?? 'admin';

/** ໃຊ້ໃນໜ້າສະເພາະ superadmin (ຕົວຢ່າງ: ລຶບ user/organizer)
 *  ຮຽກ require_superadmin() ຢູ່ເທິງສຸດຂອງໄຟລ໌ທີ່ຕ້ອງການ */
function require_superadmin(): void {
    global $admin_role;
    if ($admin_role !== 'superadmin') {
        die('<p style="font-family:sans-serif;padding:40px;text-align:center">
            ⛔ ອະນຸຍາດສະເພາະ Superadmin ເທົ່ານັ້ນ. <a href="admin_dashboard.php">← ກັບໜ້າຫຼັກ</a></p>');
    }
}
