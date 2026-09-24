<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
$requests = $pdo->query("SELECT r.*, u.email, u.full_name FROM organizer_requests r JOIN users u ON u.user_id = r.user_id WHERE r.status = 'pending' ORDER BY r.created_at ASC")->fetchAll();
$active = 'organizer_requests';
?><!doctype html>
<html lang="lo">
<head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Organizer Requests | Admin - LAOeventMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/admin.css">
    <style>
        .request-actions { display:flex; gap:8px; align-items:center; }
        .btn-approve, .btn-reject { color:#fff; padding:7px 12px; border-radius:6px; text-decoration:none; font-size:12px; }
        .btn-approve { background:#10B981; }
        .btn-reject { background:#EF4444; }
        .btn-approve:hover { background:#059669; }
        .btn-reject:hover { background:#DC2626; }
    </style>
</head>
<body>
<?php include __DIR__ . '/admin_topbar.php'; ?>
<div class="app-shell">
    <?php include __DIR__ . '/admin_sidebar.php'; ?>
    <div class="content">
        <p class="page-title" style="margin-bottom:16px">Organizer Requests</p>
        <div class="panel">
            <?php if (!$requests): ?>
                <div class="empty-state"><i class="ti ti-user-check"></i> No pending organizer requests</div>
            <?php else: ?>
                <table class="data-table">
                    <tr>
                        <th>User</th><th>Email</th><th>Organizer</th><th>Company</th><th>Phone</th><th>Action</th>
                    </tr>
                    <?php foreach ($requests as $request): ?>
                        <tr>
                            <td><?= htmlspecialchars($request['full_name']) ?></td>
                            <td><?= htmlspecialchars($request['email']) ?></td>
                            <td><?= htmlspecialchars($request['organizer_name']) ?></td>
                            <td><?= htmlspecialchars($request['company_name'] ?: '-') ?></td>
                            <td><?= htmlspecialchars($request['phone'] ?: '-') ?></td>
                            <td>
                                <div class="request-actions">
                                    <a class="btn-approve" href="admin_organizer_request_action.php?id=<?= (int)$request['request_id'] ?>&action=approve" onclick="return confirm('Approve this organizer request?')">Approve</a>
                                    <a class="btn-reject" href="admin_organizer_request_action.php?id=<?= (int)$request['request_id'] ?>&action=reject" onclick="return confirm('Reject this organizer request?')">Reject</a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
