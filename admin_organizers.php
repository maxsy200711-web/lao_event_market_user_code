<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
$active = 'organizers';
$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$totalRows = (int)$pdo->query('SELECT COUNT(*) FROM organizers')->fetchColumn();
$page = min($page, max(1, (int)ceil($totalRows / $perPage)));
$offset = ($page - 1) * $perPage;
$rows = $pdo->query("SELECT o.organizer_id, o.username, o.organizer_name, o.company_name, o.email, o.phone, o.status, o.is_verified
    FROM organizers o ORDER BY FIELD(o.status, 'pending', 'approved', 'rejected'), o.organizer_id DESC LIMIT $perPage OFFSET $offset")->fetchAll();
?>
<!doctype html>
<html lang="lo"><head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Manage Organizers | LAOeventMarket Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/admin.css?v=20261002-pagination">
    <style>.org-actions{display:flex;gap:6px;align-items:center;flex-wrap:wrap}.org-actions form{display:inline}.btn-approve{background:#10B981;color:#fff}.btn-reject{background:#EF4444;color:#fff}</style>
</head><body>
<?php include __DIR__ . '/admin_topbar.php'; ?>
<div class="app-shell">
    <?php include __DIR__ . '/admin_sidebar.php'; ?>
    <main class="content">
        <p class="page-title" style="margin-bottom:16px">Manage Organizers</p>
        <?php if (isset($_GET['saved'])): ?><div class="panel" style="color:#047857">Organizer information saved.</div><?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?><div class="panel" style="color:#047857">Organizer deleted.</div><?php endif; ?>
        <?php if (isset($_GET['error'])): ?><div class="panel" style="color:#B91C1C">Could not complete the action. The email may already be in use or related records may prevent deletion.</div><?php endif; ?>
        <section class="panel">
            <p style="margin-bottom:14px"><a href="admin_organizer_form.php" class="btn" style="background:#10B981;color:#fff">+ Add Organizer</a></p>
            <?php if (!$rows): ?>
                <div class="empty-state"><i class="ti ti-building-store"></i>No organizers registered.</div>
            <?php else: ?>
                <table class="data-table"><thead><tr><th>Organizer</th><th>Company</th><th>Contact email</th><th>Phone</th><th>Login username</th><th>Approval</th><th>Actions</th></tr></thead><tbody>
                <?php foreach ($rows as $row):
                    $status = strtolower($row['status'] ?? 'pending');
                    $approved = $status === 'approved' && (int)$row['is_verified'] === 1;
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['organizer_name']) ?></td>
                        <td><?= htmlspecialchars($row['company_name'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?: '-') ?></td>
                        <td><?= htmlspecialchars($row['username'] ?? '-') ?></td>
                        <?php $displayStatus = $status === 'approved' && !$approved ? 'pending' : $status; ?>
                        <td><span class="badge badge-<?= htmlspecialchars($displayStatus) ?>"><?= htmlspecialchars(ucfirst($displayStatus)) ?></span></td>
                        <td><div class="org-actions">
                            <a href="admin_organizer_form.php?id=<?= (int)$row['organizer_id'] ?>" class="btn btn-outline">Edit</a>
                            <?php if (!$approved): ?><form method="post" action="admin_organizer_save.php"><input type="hidden" name="action" value="status"><input type="hidden" name="organizer_id" value="<?= (int)$row['organizer_id'] ?>"><input type="hidden" name="status" value="approved"><button class="btn btn-approve" type="submit">Approve</button></form><?php endif; ?>
                            <?php if ($status !== 'rejected'): ?><form method="post" action="admin_organizer_save.php"><input type="hidden" name="action" value="status"><input type="hidden" name="organizer_id" value="<?= (int)$row['organizer_id'] ?>"><input type="hidden" name="status" value="rejected"><button class="btn btn-reject" type="submit">Reject</button></form><?php endif; ?>
                            <form method="post" action="admin_organizer_save.php" onsubmit="return confirm('Delete this organizer? Its related events and stalls may also be affected.')"><input type="hidden" name="action" value="delete"><input type="hidden" name="organizer_id" value="<?= (int)$row['organizer_id'] ?>"><button class="btn btn-danger" type="submit">Delete</button></form>
                        </div></td>
                    </tr>
                <?php endforeach; ?></tbody></table>
            <?php endif; ?>
            <?php require_once __DIR__ . '/admin_pagination.php'; admin_pagination($totalRows, $perPage, $page); ?>
        </section>
    </main>
</div>
<script src="assets/site-i18n.js?v=20260930-admin-locale"></script>
    </body></html>
