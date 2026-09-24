<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';

$id = (int)($_GET['id'] ?? 0);
$user = [
    'username' => '',
    'full_name' => '',
    'email' => '',
    'phone' => '',
    'role_id' => 'USER',
];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT username, full_name, email, phone, role_id FROM users WHERE user_id = ?');
    $stmt->execute([$id]);
    $user = $stmt->fetch() ?: $user;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $id ? 'Edit User' : 'Add User' ?> | Admin</title>
    <link rel="stylesheet" href="assets/admin.css">
    <style>
        .form-card { max-width: 620px; background: #fff; border: 1px solid #e2e8f0; border-radius: 10px; padding: 22px; }
        .form-group { margin-bottom: 14px; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .form-control { width: 100%; height: 42px; box-sizing: border-box; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0 12px; }
        .form-actions { display: flex; gap: 8px; margin-top: 18px; }
        .btn-save, .btn-cancel { padding: 10px 16px; border-radius: 7px; border: 0; text-decoration: none; font-size: 13px; cursor: pointer; }
        .btn-save { background: #10b981; color: #fff; }
        .btn-cancel { background: #f1f5f9; color: #334155; }
    </style>
</head>
<body>
    <?php include __DIR__ . '/admin_topbar.php'; ?>
    <div class="app-shell">
        <?php $active = 'users'; include __DIR__ . '/admin_sidebar.php'; ?>
        <div class="content">
            <p class="page-title" style="margin-bottom:16px"><?= $id ? 'Edit User' : 'Add User' ?></p>
            <div class="form-card">
                <form method="post" action="admin_user_save.php">
                    <input type="hidden" name="user_id" value="<?= $id ?>">
                    <div class="form-group">
                        <label>Username</label>
                        <input class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>Full name</label>
                        <input class="form-control" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>Phone</label>
                        <input class="form-control" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label>Role</label>
                        <select class="form-control" name="role_id" required>
                            <option value="USER" <?= $user['role_id'] === 'USER' ? 'selected' : '' ?>>Normal User</option>
                            <option value="ORGANIZER" <?= $user['role_id'] === 'ORGANIZER' ? 'selected' : '' ?>>Organizer</option>
                            <option value="ADMIN" <?= $user['role_id'] === 'ADMIN' ? 'selected' : '' ?>>Administrator</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Password <?= $id ? '(leave blank to keep current password)' : '' ?></label>
                        <input type="password" class="form-control" name="password" minlength="6" <?= $id ? '' : 'required' ?> autocomplete="new-password">
                    </div>
                    <div class="form-actions">
                        <a class="btn-cancel" href="admin_users.php?tab=users">Cancel</a>
                        <button class="btn-save" type="submit">Save user</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
