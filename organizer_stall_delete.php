<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

$stall_id = (int) ($_GET['id'] ?? 0);
$event_id = (int) ($_GET['event_id'] ?? 0);

if ($stall_id) {
    // ລຶບໄດ້ສະເພາະຮ້ານທີ່ຢູ່ໃນງານຂອງ organizer ຄົນນີ້ເທົ່ານັ້ນ
    $stmt = $pdo->prepare("DELETE es FROM event_stalls es
                            JOIN events e ON e.event_id = es.event_id
                            WHERE es.stall_id = ? AND e.organizer_id = ?");
    $stmt->execute([$stall_id, $organizer_id]);
}

header("Location: organizer_stalls.php?event_id=$event_id&deleted=1");
exit;
