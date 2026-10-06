<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: admin_organizers.php');
    exit;
}

$action = $_POST['action'] ?? 'save';
$organizerId = (int)($_POST['organizer_id'] ?? 0);
$statuses = ['pending', 'approved', 'rejected'];

try {
    if ($action === 'status' && $organizerId > 0) {
        $status = strtolower(trim($_POST['status'] ?? ''));
        if (!in_array($status, $statuses, true)) throw new InvalidArgumentException('Invalid status.');
        $stmt = $pdo->prepare('UPDATE organizers SET status = ?, is_verified = ? WHERE organizer_id = ?');
        $stmt->execute([$status, $status === 'approved' ? 1 : 0, $organizerId]);
        header('Location: admin_organizers.php?saved=1');
        exit;
    }

    if ($action === 'delete' && $organizerId > 0) {
        $stmt = $pdo->prepare('DELETE FROM organizers WHERE organizer_id = ?');
        $stmt->execute([$organizerId]);
        header('Location: admin_organizers.php?deleted=1');
        exit;
    }

    if ($action !== 'save') throw new InvalidArgumentException('Invalid action.');

    $name = trim($_POST['organizer_name'] ?? '');
    $company = trim($_POST['company_name'] ?? '');
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL);
    $phone = trim($_POST['phone'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    $status = strtolower(trim($_POST['status'] ?? ($organizerId > 0 ? 'pending' : 'approved')));

    if ($name === '' || $company === '' || !$email || !preg_match('/^[0-9+ ]{8,20}$/', $phone) || $username === '' || strlen($username) > 100 || strlen($name) > 100 || strlen($company) > 150 || !in_array($status, $statuses, true)) {
        throw new InvalidArgumentException('Invalid organizer data.');
    }

    $pdo->beginTransaction();
    $accountStmt = $pdo->prepare('SELECT username FROM organizers WHERE organizer_id = ? FOR UPDATE');
    $accountStmt->execute([$organizerId]);
    $accountExists = (bool)$accountStmt->fetch();

    if (!$accountExists && strlen($password) < 6) throw new InvalidArgumentException('A password of at least 6 characters is required for a new login.');
    if (($password !== '' || $confirmPassword !== '') && (strlen($password) < 6 || $password !== $confirmPassword)) {
        throw new InvalidArgumentException('Password confirmation does not match.');
    }

    $stmt = $pdo->prepare('SELECT 1 FROM organizers WHERE username = ? AND organizer_id <> ? LIMIT 1');
    $stmt->execute([$username, $organizerId]);
    if ($stmt->fetchColumn()) throw new RuntimeException('Organizer username is already in use.');
    $stmt = $pdo->prepare('SELECT 1 FROM organizers WHERE email = ? AND organizer_id <> ? LIMIT 1');
    $stmt->execute([$email, $organizerId]);
    if ($stmt->fetchColumn()) throw new RuntimeException('Organizer email is already in use.');

    $verified = $status === 'approved' ? 1 : 0;
    if ($organizerId > 0) {
        if ($password !== '') {
            $stmt = $pdo->prepare('UPDATE organizers SET username = ?, password = ?, organizer_name = ?, company_name = ?, email = ?, phone = ?, status = ?, is_verified = ? WHERE organizer_id = ?');
            $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $name, $company, $email, $phone, $status, $verified, $organizerId]);
        } else {
            $stmt = $pdo->prepare('UPDATE organizers SET username = ?, organizer_name = ?, company_name = ?, email = ?, phone = ?, status = ?, is_verified = ? WHERE organizer_id = ?');
            $stmt->execute([$username, $name, $company, $email, $phone, $status, $verified, $organizerId]);
        }
        if ($stmt->rowCount() === 0) {
            $check = $pdo->prepare('SELECT 1 FROM organizers WHERE organizer_id = ?');
            $check->execute([$organizerId]);
            if (!$check->fetchColumn()) throw new RuntimeException('Organizer not found.');
        }
    } else {
        $stmt = $pdo->prepare('INSERT INTO organizers (username, password, organizer_name, company_name, email, phone, status, is_verified) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT), $name, $company, $email, $phone, $status, $verified]);
        $organizerId = (int)$pdo->lastInsertId();
    }

    $pdo->commit();
    header('Location: admin_organizers.php?saved=1');
    exit;
} catch (Throwable $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    $destination = $action === 'save'
        ? 'admin_organizer_form.php' . ($organizerId > 0 ? '?id=' . $organizerId . '&' : '?') . 'error=save'
        : 'admin_organizers.php?error=action';
    header('Location: ' . $destination);
    exit;
}
