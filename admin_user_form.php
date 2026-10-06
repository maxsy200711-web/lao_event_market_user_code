<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';

$id = (int)($_GET['id'] ?? 0);
$accountType = ($_GET['type'] ?? 'users') === 'admins' ? 'admins' : 'users';
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
    $accountType = ($user['role_id'] ?? 'USER') === 'ADMIN' ? 'admins' : 'users';
}

?>
<!doctype html>
<html lang="lo">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $id ? 'Edit User' : 'Add User' ?> | Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
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
            <p class="page-title" style="margin-bottom:16px"><?= $id ? 'ແກ້ໄຂບັນຊີ' : ($accountType === 'admins' ? 'ເພີ່ມຜູ້ດູແລ' : 'ເພີ່ມຜູ້ໃຊ້') ?></p>
            <div class="form-card">
                <form method="post" action="admin_user_save.php">
                    <input type="hidden" name="user_id" value="<?= $id ?>">
                    <input type="hidden" name="account_type" value="<?= htmlspecialchars($accountType) ?>">
                    <div class="form-group">
                        <label>ຊື່ຜູ້ໃຊ້</label>
                        <input class="form-control" name="username" value="<?= htmlspecialchars($user['username']) ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>ຊື່ເຕັມ</label>
                        <input class="form-control" name="full_name" value="<?= htmlspecialchars($user['full_name']) ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>ອີເມວ</label>
                        <input type="email" class="form-control" name="email" value="<?= htmlspecialchars($user['email']) ?>" required maxlength="100">
                    </div>
                    <div class="form-group">
                        <label>ເບີໂທ</label>
                        <input class="form-control" name="phone" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" maxlength="20">
                    </div>
                    <div class="form-group">
                        <label>ລະຫັດຜ່ານ <?= $id ? '(ປະໄວ້ຫວ່າງເພື່ອຄົງລະຫັດຜ່ານເກົ່າ)' : '' ?></label>
                        <input type="password" class="form-control" name="password" minlength="6" <?= $id ? '' : 'required' ?> autocomplete="new-password">
                    </div>
                    <div class="form-actions">
                        <a class="btn-cancel" href="admin_users.php?type=<?= htmlspecialchars($accountType) ?>">ຍົກເລີກ</a>
                        <button class="btn-save" type="submit">ບັນທຶກ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<script src="assets/site-i18n.js?v=20260930-admin-locale"></script>
    </body>
</html>
