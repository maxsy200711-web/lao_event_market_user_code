<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
if (empty($_SESSION['admin_csrf'])) $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['admin_csrf'];
$error = '';

function admin_stall_image_url(?string $image): string {
    $image = trim((string)$image);
    if ($image === '') return '';
    if (preg_match('~^https?://~i', $image)) return $image;
    return str_starts_with(ltrim($image, '/'), 'uploads/') ? ltrim($image, '/') : 'uploads/' . ltrim($image, '/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) {
        $error = 'Security token expired. Refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        $id = (int)($_POST['stall_id'] ?? 0);
        try {
            if ($action === 'delete' && $id > 0) {
                $stmt = $pdo->prepare('DELETE FROM event_stalls WHERE stall_id = ?');
                $stmt->execute([$id]);
            } elseif (in_array($action, ['add', 'edit'], true)) {
                $eventId = (int)($_POST['event_id'] ?? 0);
                $stallName = trim((string)($_POST['stall_name'] ?? ''));
                $stallNumber = trim((string)($_POST['stall_number'] ?? ''));
                $categories = trim((string)($_POST['categories'] ?? ''));
                $description = trim((string)($_POST['description'] ?? ''));
                $contact = trim((string)($_POST['contact_info'] ?? ''));
                $image = trim((string)($_POST['image'] ?? ''));
                if (!$eventId || $stallName === '') throw new RuntimeException('Event and stall name are required.');
                if ($action === 'edit' && $id > 0) {
                    $stmt = $pdo->prepare('UPDATE event_stalls SET event_id=?, stall_name=?, stall_number=?, categories=?, description=?, contact_info=?, image=? WHERE stall_id=?');
                    $stmt->execute([$eventId,$stallName,$stallNumber,$categories,$description,$contact,$image,$id]);
                } else {
                    $stmt = $pdo->prepare('INSERT INTO event_stalls (event_id, stall_name, stall_number, categories, description, contact_info, image) VALUES (?,?,?,?,?,?,?)');
                    $stmt->execute([$eventId,$stallName,$stallNumber,$categories,$description,$contact,$image]);
                }
            }
            header('Location: admin_stalls.php?saved=1');
            exit;
        } catch (Throwable $e) {
            $error = $e instanceof PDOException ? 'Could not save the stall. Check that the selected event exists.' : $e->getMessage();
        }
    }
}

$editId = (int)($_GET['edit'] ?? 0);
$editing = null;
if ($editId) {
    $stmt = $pdo->prepare('SELECT * FROM event_stalls WHERE stall_id = ?');
    $stmt->execute([$editId]);
    $editing = $stmt->fetch() ?: null;
}
$events = $pdo->query('SELECT event_id, title FROM events ORDER BY event_id DESC')->fetchAll();
$categories = $pdo->query('SELECT category_name, category_name_en FROM categories ORDER BY category_name')->fetchAll();
$perPage = 7;
$page = max(1, (int)($_GET['page'] ?? 1));
$totalRows = (int)$pdo->query('SELECT COUNT(*) FROM event_stalls')->fetchColumn();
$page = min($page, max(1, (int)ceil($totalRows / $perPage)));
$offset = ($page - 1) * $perPage;
$stalls = $pdo->query("SELECT s.*, e.title AS event_title FROM event_stalls s LEFT JOIN events e ON e.event_id=s.event_id ORDER BY s.stall_id DESC LIMIT $perPage OFFSET $offset")->fetchAll();
?>
<!doctype html><html lang="lo"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Manage Event Stalls | Admin</title><link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"><link rel="stylesheet" href="assets/admin.css?v=20261006-stall-images"></head><body>
<?php include __DIR__ . '/admin_topbar.php'; ?><div class="app-shell"><?php $active='stalls'; include __DIR__ . '/admin_sidebar.php'; ?><main class="content">
<p class="page-title" style="margin-bottom:16px">Manage Event Stalls</p>
<?php if($error): ?><div class="panel" style="color:#B91C1C"><?= htmlspecialchars($error) ?></div><?php elseif(isset($_GET['saved'])): ?><div class="panel" style="color:#047857">Changes saved.</div><?php endif; ?>
<section class="panel"><h2><?= $editing?'Edit':'Add' ?> Stall</h2><form method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>"><input type="hidden" name="action" value="<?= $editing?'edit':'add' ?>"><input type="hidden" name="stall_id" value="<?= (int)($editing['stall_id']??0) ?>">
<label class="field-label">Event</label><select class="f-input" name="event_id" required><option value="">Select event</option><?php foreach($events as $event): ?><option value="<?= (int)$event['event_id'] ?>" <?= (int)($editing['event_id']??0)===(int)$event['event_id']?'selected':'' ?>>#<?= (int)$event['event_id'] ?> — <?= htmlspecialchars($event['title']) ?></option><?php endforeach; ?></select>
<label class="field-label">Stall name</label><input class="f-input" name="stall_name" maxlength="150" required value="<?= htmlspecialchars($editing['stall_name']??'') ?>">
<label class="field-label">Stall number</label><input class="f-input" name="stall_number" maxlength="20" value="<?= htmlspecialchars($editing['stall_number']??'') ?>">
<label class="field-label">Category</label><select class="f-input" name="categories" required><option value="">ເລືອກໝວດໝູ່</option><?php foreach($categories as $category): $categoryName=$category['category_name']; $categoryLabel=$categoryName . (!empty($category['category_name_en']) ? ' (' . $category['category_name_en'] . ')' : ''); ?><option value="<?= htmlspecialchars($categoryName) ?>" <?= ($editing['categories']??'')===$categoryName?'selected':'' ?>><?= htmlspecialchars($categoryLabel) ?></option><?php endforeach; ?></select>
<label class="field-label">Description</label><textarea class="f-input" name="description" rows="3"><?= htmlspecialchars($editing['description']??'') ?></textarea>
<label class="field-label">Contact information</label><input class="f-input" name="contact_info" maxlength="100" value="<?= htmlspecialchars($editing['contact_info']??'') ?>">
<label class="field-label">Image path / URL</label><input class="f-input" name="image" maxlength="255" value="<?= htmlspecialchars($editing['image']??'') ?>">
<button class="btn" style="background:#10B981;color:#fff" type="submit">Save Stall</button><?php if($editing): ?> <a class="btn btn-outline" href="admin_stalls.php">Cancel</a><?php endif; ?></form></section>
<section class="panel"><h2>All Stalls</h2>
<?php if (!$stalls): ?><div class="empty-state">No stalls found.</div><?php else: ?>
<table class="data-table"><thead><tr><th>ID</th><th>Image</th><th>Event</th><th>Stall</th><th>Number</th><th>Category</th><th>Contact</th><th>Actions</th></tr></thead><tbody>
<?php foreach ($stalls as $s): $imageUrl = admin_stall_image_url($s['image'] ?? ''); ?>
<tr>
    <td><?= (int)$s['stall_id'] ?></td>
    <td><?php if ($imageUrl !== ''): ?><img class="stall-preview" src="<?= htmlspecialchars($imageUrl, ENT_QUOTES, 'UTF-8') ?>" alt="<?= htmlspecialchars($s['stall_name'], ENT_QUOTES, 'UTF-8') ?>" loading="lazy"><?php else: ?><span class="stall-preview stall-preview-empty"><i class="ti ti-photo"></i></span><?php endif; ?></td>
    <td><?= htmlspecialchars($s['event_title'] ?? '—') ?></td>
    <td><?= htmlspecialchars($s['stall_name']) ?></td>
    <td><?= htmlspecialchars($s['stall_number'] ?? '') ?></td>
    <td><?= htmlspecialchars($s['categories'] ?? '') ?></td>
    <td><?= htmlspecialchars($s['contact_info'] ?? '') ?></td>
    <td><a class="btn btn-outline" href="?edit=<?= (int)$s['stall_id'] ?>">Edit</a> <form method="post" style="display:inline" onsubmit="return confirm('Delete this stall?')"><input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="stall_id" value="<?= (int)$s['stall_id'] ?>"><button class="btn btn-danger">Delete</button></form></td>
</tr>
<?php endforeach; ?></tbody></table><?php endif; ?></section>
<?php require_once __DIR__ . '/admin_pagination.php'; admin_pagination($totalRows, $perPage, $page); ?></main></div></body></html>
