<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
if (empty($_SESSION['admin_csrf'])) $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['admin_csrf'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'delete') {
    if (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) { http_response_code(400); exit('Invalid request.'); }
    $deleteId = (int)($_POST['event_id'] ?? 0);
    if ($deleteId > 0) {
        try {
            $pdo->beginTransaction();
            $pdo->prepare('DELETE FROM reviews WHERE event_id = ?')->execute([$deleteId]);
            $pdo->prepare('DELETE FROM event_stalls WHERE event_id = ?')->execute([$deleteId]);
            $pdo->prepare('DELETE FROM events WHERE event_id = ?')->execute([$deleteId]);
            $pdo->commit();
            header('Location: admin_events.php?deleted=1'); exit;
        } catch (Throwable $e) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            header('Location: admin_events.php?error=delete'); exit;
        }
    }
}
$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$totalRows = (int)$pdo->query('SELECT COUNT(*) FROM events')->fetchColumn();
$page = min($page, max(1, (int)ceil($totalRows / $perPage)));
$offset = ($page - 1) * $perPage;
$events = $pdo->query("SELECT e.event_id,e.title,e.start_date,e.status,o.organizer_name FROM events e LEFT JOIN organizers o ON o.organizer_id=e.organizer_id ORDER BY e.event_id DESC LIMIT $perPage OFFSET $offset")->fetchAll();
?>
<!doctype html><html lang="lo"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Manage Events | Admin</title><link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"><link rel="stylesheet" href="assets/admin.css?v=20261002-pagination"></head><body>
<?php include __DIR__ . '/admin_topbar.php'; ?><div class="app-shell"><?php $active='events'; include __DIR__ . '/admin_sidebar.php'; ?><main class="content"><p class="page-title" style="margin-bottom:16px">Manage Events</p><?php if(isset($_GET['saved'])): ?><div class="panel" style="color:#047857">Event updated.</div><?php endif; ?>
<section class="panel"><h2>All Events</h2><?php if(!$events): ?><div class="empty-state">No events found.</div><?php else: ?><table class="data-table"><thead><tr><th>ID</th><th>Event</th><th>Organizer</th><th>Start date</th><th>Status</th><th>Actions</th></tr></thead><tbody><?php foreach($events as $e): ?><tr><td><?= (int)$e['event_id'] ?></td><td><?= htmlspecialchars($e['title']) ?></td><td><?= htmlspecialchars($e['organizer_name']??'—') ?></td><td><?= htmlspecialchars($e['start_date']) ?></td><td><?= htmlspecialchars(ucfirst($e['status'])) ?></td><td><a class="btn btn-outline" href="admin_event_form.php?id=<?= (int)$e['event_id'] ?>">Edit all details</a></td></tr><?php endforeach; ?></tbody></table><?php endif; ?></section>
<?php require_once __DIR__ . '/admin_pagination.php'; admin_pagination($totalRows, $perPage, $page); ?></main></div></body></html>
