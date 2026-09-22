<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";

$id = (int) ($_GET['id'] ?? 0);
if ($id) {
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE review_id = ?");
    $stmt->execute([$id]);
}

header("Location: admin_reviews.php?deleted=1");
exit;
