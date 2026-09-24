<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
$requestId = (int)($_GET['id'] ?? 0);
$action = $_GET['action'] ?? '';
if (!$requestId || !in_array($action, ['approve', 'reject'], true)) { header('Location: admin_organizer_requests.php'); exit; }

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare("SELECT r.*, u.email, u.password, u.phone AS user_phone FROM organizer_requests r JOIN users u ON u.user_id = r.user_id WHERE r.request_id = ? AND r.status = 'pending' FOR UPDATE");
    $stmt->execute([$requestId]); $r = $stmt->fetch();
    if (!$r) throw new RuntimeException('Request is no longer pending.');

    if ($action === 'approve') {
        $stmt = $pdo->prepare('SELECT o.organizer_id FROM organizer_users ou JOIN organizers o ON o.organizer_id = ou.organizer_id WHERE ou.user_id = ? LIMIT 1');
        $stmt->execute([$r['user_id']]); $org = $stmt->fetch();
        if ($org) {
            $stmt = $pdo->prepare("UPDATE organizers SET organizer_name = ?, company_name = ?, status = 'approved', is_verified = 1 WHERE organizer_id = ?");
            $stmt->execute([$r['organizer_name'], $r['company_name'], $org['organizer_id']]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO organizers (organizer_name, company_name, status, is_verified) VALUES (?, ?, 'approved', 1)");
            $stmt->execute([$r['organizer_name'], $r['company_name']]);
            $org = ['organizer_id' => $pdo->lastInsertId()];
        }
        $stmt = $pdo->prepare("INSERT INTO organizer_users (organizer_id, user_id) VALUES (?, ?) ON DUPLICATE KEY UPDATE organizer_id = VALUES(organizer_id)");
        $stmt->execute([$org['organizer_id'], $r['user_id']]);
        $stmt = $pdo->prepare("UPDATE users SET role_id = 'ORGANIZER' WHERE user_id = ?"); $stmt->execute([$r['user_id']]);
        $stmt = $pdo->prepare("UPDATE organizer_requests SET status = 'approved', reviewed_at = NOW(), reviewed_by = ? WHERE request_id = ?"); $stmt->execute([$admin_id, $requestId]);
    } else {
        $stmt = $pdo->prepare("UPDATE organizer_requests SET status = 'rejected', reviewed_at = NOW(), reviewed_by = ? WHERE request_id = ?"); $stmt->execute([$admin_id, $requestId]);
    }
    $pdo->commit();
} catch (Throwable $e) { if ($pdo->inTransaction()) $pdo->rollBack(); }
header('Location: admin_organizer_requests.php'); exit;
