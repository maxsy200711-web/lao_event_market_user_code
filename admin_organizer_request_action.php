<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';

$requestId = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
if (!$requestId || !in_array($action, ['approve', 'reject'], true)) {
    header('Location: admin_organizer_requests.php');
    exit;
}

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("SELECT * FROM organizer_requests WHERE request_id = ? AND status = 'pending' FOR UPDATE");
    $stmt->execute([$requestId]);
    $request = $stmt->fetch();
    if (!$request) throw new RuntimeException('Request is no longer pending.');

    if ($action === 'approve') {
        if (empty($request['username']) || empty($request['email']) || empty($request['password'])) {
            throw new RuntimeException('Request is missing verified login credentials.');
        }
        $stmt = $pdo->prepare('SELECT organizer_id FROM organizers WHERE email = ? OR username = ? LIMIT 1 FOR UPDATE');
        $stmt->execute([$request['email'], $request['username']]);
        $organizerId = (int)$stmt->fetchColumn();

        if ($organizerId > 0) {
            $stmt = $pdo->prepare("UPDATE organizers SET username = ?, password = ?, organizer_name = ?, company_name = ?, email = ?, phone = ?, status = 'approved', is_verified = 1 WHERE organizer_id = ?");
            $stmt->execute([$request['username'], $request['password'], $request['organizer_name'], $request['company_name'], $request['email'], $request['phone'], $organizerId]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO organizers (username, password, organizer_name, company_name, email, phone, status, is_verified) VALUES (?, ?, ?, ?, ?, ?, 'approved', 1)");
            $stmt->execute([$request['username'], $request['password'], $request['organizer_name'], $request['company_name'], $request['email'], $request['phone']]);
        }

        $stmt = $pdo->prepare("UPDATE organizer_requests SET status = 'approved', password = NULL, reviewed_at = NOW(), reviewed_by = ? WHERE request_id = ?");
        $stmt->execute([$admin_id, $requestId]);
    } else {
        $stmt = $pdo->prepare("UPDATE organizer_requests SET status = 'rejected', password = NULL, reviewed_at = NOW(), reviewed_by = ? WHERE request_id = ?");
        $stmt->execute([$admin_id, $requestId]);
    }

    $pdo->commit();
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
}
header('Location: admin_organizer_requests.php');
exit;
