<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';

$id = (int)($_GET['id'] ?? 0);
$org = [
    'organizer_name' => '',
    'company_name' => '',
    'email' => '',
    'phone' => '',
    'status' => 'approved',
    'is_verified' => 1,
];
$account = ['username' => '', 'has_account' => false];

if ($id > 0) {
    $stmt = $pdo->prepare('SELECT username, organizer_name, company_name, email, phone, status, is_verified FROM organizers WHERE organizer_id = ?');
    $stmt->execute([$id]);
    $org = $stmt->fetch() ?: $org;
    $account = ['username' => $org['username'] ?? '', 'has_account' => !empty($org['username'])];
}

$active = 'organizers';
$currentStatus = strtolower($org['status'] ?? 'approved');
if ($currentStatus === 'approved' && (int)$org['is_verified'] !== 1) $currentStatus = 'pending';
?>
<!doctype html>
<html lang="lo"><head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?= $id ? 'Edit Organizer' : 'Add Organizer' ?> | LAOeventMarket Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/admin.css">
    <style>
        .edit-panel { max-width: 760px; }
        .edit-form label { display: block; margin: 14px 0 6px; font-size: 13px; font-weight: 600; }
        .edit-form input, .edit-form select { width: 100%; height: 40px; padding: 0 10px; border: 1px solid #E2E8F0; border-radius: 7px; }
        .help { font-size: 12px; color: #64748B; margin-top: 6px; }
        .form-actions { display: flex; gap: 8px; margin-top: 18px; }
    </style>
</head><body>
<?php include __DIR__ . '/admin_topbar.php'; ?>
<div class="app-shell">
    <?php include __DIR__ . '/admin_sidebar.php'; ?>
    <main class="content">
        <p class="page-title" style="margin-bottom:16px"><?= $id ? 'Edit Organizer' : 'Add Organizer' ?></p>
        <?php if (isset($_GET['error'])): ?><div class="panel" style="color:#B91C1C">Unable to save. Check the required fields and make sure the username and email are unique.</div><?php endif; ?>
        <section class="panel edit-panel">
            <form class="edit-form" method="post" action="admin_organizer_save.php">
                <input type="hidden" name="action" value="save">
                <input type="hidden" name="organizer_id" value="<?= $id ?>">

                <label for="username">Username *</label>
                <input id="username" name="username" maxlength="100" required value="<?= htmlspecialchars($account['username'], ENT_QUOTES, 'UTF-8') ?>" autocomplete="username">

                <label for="orgName">Organizer name *</label>
                <input id="orgName" name="organizer_name" maxlength="100" required value="<?= htmlspecialchars($org['organizer_name'], ENT_QUOTES, 'UTF-8') ?>">

                <label for="companyName">Company name *</label>
                <input id="companyName" name="company_name" maxlength="150" required value="<?= htmlspecialchars($org['company_name'] ?? '', ENT_QUOTES, 'UTF-8') ?>">

                <label for="email">Email *</label>
                <input id="email" type="email" name="email" maxlength="100" required value="<?= htmlspecialchars($org['email'] ?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="email">

                <label for="phone">Phone number *</label>
                <input id="phone" name="phone" type="tel" pattern="[0-9+ ]{8,20}" maxlength="20" required value="<?= htmlspecialchars($org['phone'] ?? '', ENT_QUOTES, 'UTF-8') ?>" autocomplete="tel">

                <label for="password">Password <?= $account['has_account'] ? '(leave blank to keep current password)' : '*' ?></label>
                <input id="password" type="password" name="password" minlength="6" <?= $account['has_account'] ? '' : 'required' ?> autocomplete="new-password">

                <label for="confirmPassword">Confirm password <?= $account['has_account'] ? '(only when changing password)' : '*' ?></label>
                <input id="confirmPassword" type="password" name="confirm_password" minlength="6" <?= $account['has_account'] ? '' : 'required' ?> autocomplete="new-password">
                <?php if ($account['has_account']): ?><p class="help">The current password cannot be viewed. Enter a new password in both fields to change it.</p><?php endif; ?>

                <?php if ($id > 0): ?>
                    <label for="status">Approval status</label>
                    <select id="status" name="status" required>
                        <?php foreach (['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected'] as $value => $label): ?>
                            <option value="<?= $value ?>" <?= $currentStatus === $value ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php else: ?>
                    <input type="hidden" name="status" value="approved">
                <?php endif; ?>

                <div class="form-actions">
                    <a href="admin_organizers.php" class="btn btn-outline">Cancel</a>
                    <button class="btn" style="background:#10B981;color:#fff" type="submit">Save Organizer</button>
                </div>
            </form>
        </section>
    </main>
</div>
<script src="assets/site-i18n.js?v=20260930-admin-locale"></script>
    </body></html>
