<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin_users.php?tab=users'); exit; }
$id = (int)($_POST['user_id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$fullName = trim($_POST['full_name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$roleId = strtoupper(trim($_POST['role_id'] ?? 'USER'));
if (!in_array($roleId, ['USER', 'ORGANIZER', 'ADMIN'], true)) { header('Location: admin_user_form.php' . ($id ? '?id='.$id : '') . '&error=role'); exit; }
if ($username === '' || $fullName === '' || !$email || ($id === 0 && strlen($password) < 6) || ($id > 0 && $password !== '' && strlen($password) < 6)) { header('Location: admin_user_form.php' . ($id ? '?id='.$id : '') . '&error=invalid'); exit; }
try {
    $pdo->beginTransaction();
    if ($id > 0) {
        if ($password !== '') { $stmt = $pdo->prepare('UPDATE users SET username = ?, full_name = ?, email = ?, phone = ?, password = ?, role_id = ? WHERE user_id = ?'); $stmt->execute([$username, $fullName, $email, $phone ?: null, password_hash($password, PASSWORD_DEFAULT), $roleId, $id]); }
        else { $stmt = $pdo->prepare('UPDATE users SET username = ?, full_name = ?, email = ?, phone = ?, role_id = ? WHERE user_id = ?'); $stmt->execute([$username, $fullName, $email, $phone ?: null, $roleId, $id]); }
        $userId = $id;
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, is_verified, role_id, password, phone) VALUES (?, ?, ?, 1, ?, ?, ?)");
        $stmt->execute([$username, $fullName, $email, $roleId, password_hash($password, PASSWORD_DEFAULT), $phone ?: null]);
        $userId = (int)$pdo->lastInsertId();
    }
    if ($roleId === 'ORGANIZER') {
        $stmt = $pdo->prepare('SELECT organizer_id FROM organizer_users WHERE user_id = ? LIMIT 1'); $stmt->execute([$userId]); $membership = $stmt->fetch();
        if (!$membership) {
            $stmt = $pdo->prepare("INSERT INTO organizers (organizer_name, company_name, status, is_verified) VALUES (?, NULL, 'approved', 1)"); $stmt->execute([$fullName]); $organizerId = $pdo->lastInsertId();
            $stmt = $pdo->prepare('INSERT INTO organizer_users (organizer_id, user_id) VALUES (?, ?)'); $stmt->execute([$organizerId, $userId]);
        }
    }
    $pdo->commit();
    header('Location: admin_users.php?tab=users&saved=1'); exit;
} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    header('Location: admin_user_form.php' . ($id ? '?id='.$id : '') . '&error=duplicate'); exit;
}
