<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: admin_users.php?tab=users'); 
    exit; 
}

$id = (int)($_POST['user_id'] ?? 0);
$username = trim($_POST['username'] ?? '');
$fullName = trim($_POST['full_name'] ?? '');
$email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
$phone = trim($_POST['phone'] ?? '');
$password = $_POST['password'] ?? '';
$accountType = ($_POST['account_type'] ?? 'users') === 'admins' ? 'admins' : 'users';
$roleId = $accountType === 'admins' ? 'ADMIN' : 'USER';
if ($id > 0) {
    $roleStmt = $pdo->prepare('SELECT role_id FROM users WHERE user_id = ?');
    $roleStmt->execute([$id]);
    $storedRole = $roleStmt->fetchColumn();
    if (!in_array($storedRole, ['USER', 'ADMIN'], true)) {
        header('Location: admin_users.php?type=' . $accountType . '&error=invalid');
        exit;
    }
    $roleId = $storedRole;
    $accountType = $roleId === 'ADMIN' ? 'admins' : 'users';
}

// ສ້າງ Query string ໃຫ້ຖືກຕ້ອງສະເໝີ
$errorUrl = 'admin_user_form.php?' . ($id ? 'id=' . $id . '&' : 'type=' . $accountType . '&');

if (!in_array($roleId, ['USER', 'ADMIN'], true)) { 
    header('Location: ' . $errorUrl . 'error=role'); 
    exit; 
}

if ($username === '' || $fullName === '' || !$email || ($id === 0 && strlen($password) < 6) || ($id > 0 && $password !== '' && strlen($password) < 6)) { 
    header('Location: ' . $errorUrl . 'error=invalid'); 
    exit; 
}

try {
    $pdo->beginTransaction();

    if ($id > 0) {
        if ($password !== '') { 
            $stmt = $pdo->prepare('UPDATE users SET username = ?, full_name = ?, email = ?, phone = ?, password = ?, role_id = ? WHERE user_id = ?'); 
            $stmt->execute([$username, $fullName, $email, $phone ?: null, password_hash($password, PASSWORD_DEFAULT), $roleId, $id]); 
        } else { 
            $stmt = $pdo->prepare('UPDATE users SET username = ?, full_name = ?, email = ?, phone = ?, role_id = ? WHERE user_id = ?'); 
            $stmt->execute([$username, $fullName, $email, $phone ?: null, $roleId, $id]); 
        }
        $userId = $id;
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (username, full_name, email, is_verified, role_id, password, phone) VALUES (?, ?, ?, 1, ?, ?, ?)");
        $stmt->execute([$username, $fullName, $email, $roleId, password_hash($password, PASSWORD_DEFAULT), $phone ?: null]);
        $userId = (int)$pdo->lastInsertId();
    }

    $pdo->commit();
    header('Location: admin_users.php?type=' . $accountType . '&saved=1');
    exit;

} catch (PDOException $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    // ✅ ແກ້ໄຂແລ້ວ: URL ຈະຖືກຕ້ອງສະເໝີ ບໍ່ວ່າຈະມີ $id ຫຼື ບໍ່ມີ $id
    header('Location: ' . $errorUrl . 'error=duplicate'); 
    exit;
}
