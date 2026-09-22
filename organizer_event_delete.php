<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

$event_id = (int) ($_GET['id'] ?? 0);
if ($event_id) {
    // ONLY delete if this event belongs to the logged-in organizer
    $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = ? AND organizer_id = ?");
    $stmt->execute([$event_id, $organizer_id]);
}

header("Location: organizer_events.php?deleted=1");
exit;
