<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';

$type = $_GET['type'] ?? 'user';
$id = (int)($_GET['id'] ?? 0);
if ($id > 0 && $type === 'user') {
    $stmt = $pdo->prepare('SELECT role_id FROM users WHERE user_id = ? LIMIT 1');
    $stmt->execute([$id]);
    $target = $stmt->fetch();

    if ($target) {
        $isAdmin = strtoupper($target['role_id'] ?? '') === 'ADMIN';
        $sameLoginSource = ($_SESSION['admin_source'] ?? '') === 'admins';
        $isCurrentAccount = $id === (int)$_SESSION['admin_id'];

        // Never let an admin signed in through users delete their own active account.
        if ($isAdmin && $isCurrentAccount && !$sameLoginSource) {
            header('Location: admin_users.php?error=self_delete');
            exit;
        }

        try {
            $stmt = $pdo->prepare('DELETE FROM users WHERE user_id = ?');
            $stmt->execute([$id]);
            header('Location: admin_users.php?deleted=1');
            exit;
        } catch (PDOException $e) {
            header('Location: admin_users.php?error=delete_failed');
            exit;
        }
    }
}
header('Location: admin_users.php?error=not_found');
exit;
