<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";

$event_id = (int) ($_GET['id'] ?? 0);
$action   = $_GET['action'] ?? '';

$status_map = ['approve' => 'approved', 'reject' => 'rejected'];

if ($event_id && isset($status_map[$action])) {
    $stmt = $pdo->prepare("UPDATE events SET status = ? WHERE event_id = ?");
    $stmt->execute([$status_map[$action], $event_id]);
}

header("Location: admin_approvals.php?done=1");
exit;
