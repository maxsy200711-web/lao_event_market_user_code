<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";
require_superadmin(); // ລຶບບັນຊີ ອະນຸຍາດສະເພາະ superadmin

$type = $_GET['type'] ?? '';
$id   = (int) ($_GET['id'] ?? 0);

if ($id && $type === 'user') {
    $stmt = $pdo->prepare("DELETE FROM users WHERE user_id = ?");
    $stmt->execute([$id]);
} elseif ($id && $type === 'organizer') {
    // ON DELETE CASCADE ໃນຕາຕະລາງ events ຈະລຶບງານ/ຮ້ານຄ້າຂອງ organizer ນີ້ນຳ
    $stmt = $pdo->prepare("DELETE FROM organizers WHERE organizer_id = ?");
    $stmt->execute([$id]);
}

$redirect_tab = $type === 'organizer' ? 'organizers' : 'users';
header("Location: admin_users.php?tab=$redirect_tab&deleted=1");
exit;
