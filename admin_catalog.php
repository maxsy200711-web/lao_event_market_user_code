<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';

if (empty($_SESSION['admin_csrf'])) $_SESSION['admin_csrf'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['admin_csrf'];
$kind = ($_GET['type'] ?? $_POST['type'] ?? 'categories') === 'provinces' ? 'provinces' : 'categories';
$config = [
    'categories' => ['table' => 'categories', 'id' => 'category_id', 'name' => 'category_name', 'title' => 'Categories'],
    'provinces' => ['table' => 'provinces', 'id' => 'province_id', 'name' => 'province_name', 'title' => 'Provinces'],
][$kind];
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($csrf, (string)($_POST['csrf'] ?? ''))) {
        $error = 'Security token expired. Refresh and try again.';
    } else {
        $action = $_POST['action'] ?? '';
        $id = (int)($_POST['id'] ?? 0);
        try {
            if ($action === 'delete' && $id > 0) {
                $linked = $kind === 'categories'
                    ? 'SELECT COUNT(*) FROM events WHERE category_id = ?'
                    : 'SELECT COUNT(*) FROM events WHERE province_id = ?';
                $check = $pdo->prepare($linked);
                $check->execute([$id]);
                if ((int)$check->fetchColumn() > 0) throw new RuntimeException('This item is used by an event and cannot be deleted yet.');
                $stmt = $pdo->prepare("DELETE FROM {$config['table']} WHERE {$config['id']} = ?");
                $stmt->execute([$id]);
            } elseif (in_array($action, ['add', 'edit'], true)) {
                $name = trim((string)($_POST['name'] ?? ''));
                if ($name === '') throw new RuntimeException('Name is required.');
                if ($kind === 'categories') {
                    $nameEn = trim((string)($_POST['name_en'] ?? ''));
                    if ($action === 'edit' && $id > 0) {
                        $stmt = $pdo->prepare('UPDATE categories SET category_name = ?, category_name_en = ? WHERE category_id = ?');
                        $stmt->execute([$name, $nameEn, $id]);
                    } else {
                        $stmt = $pdo->prepare('INSERT INTO categories (category_name, category_name_en) VALUES (?, ?)');
                        $stmt->execute([$name, $nameEn]);
                    }
                } elseif ($action === 'edit' && $id > 0) {
                    $stmt = $pdo->prepare('UPDATE provinces SET province_name = ? WHERE province_id = ?');
                    $stmt->execute([$name, $id]);
                } else {
                    $stmt = $pdo->prepare('INSERT INTO provinces (province_name) VALUES (?)');
                    $stmt->execute([$name]);
                }
            }
            header('Location: admin_catalog.php?type=' . rawurlencode($kind) . '&saved=1');
            exit;
        } catch (Throwable $e) {
            $error = $e instanceof PDOException ? 'Cannot save or delete this item because it is used by existing records.' : $e->getMessage();
        }
    }
}

$editId = (int)($_GET['edit'] ?? 0);
$editing = null;
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM {$config['table']} WHERE {$config['id']} = ?");
    $stmt->execute([$editId]);
    $editing = $stmt->fetch() ?: null;
}
$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$totalRows = (int)$pdo->query("SELECT COUNT(*) FROM {$config['table']}")->fetchColumn();
$page = min($page, max(1, (int)ceil($totalRows / $perPage)));
$offset = ($page - 1) * $perPage;
$rows = $pdo->query("SELECT * FROM {$config['table']} ORDER BY {$config['name']} LIMIT $perPage OFFSET $offset")->fetchAll();
?>
<!doctype html><html lang="lo"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Site Data | Admin</title><link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet"><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css"><link rel="stylesheet" href="assets/admin.css?v=20261002-pagination"></head><body>
<?php include __DIR__ . '/admin_topbar.php'; ?><div class="app-shell"><?php $active = 'catalog'; include __DIR__ . '/admin_sidebar.php'; ?><main class="content">
<p class="page-title" style="margin-bottom:16px">Manage Categories &amp; Provinces</p>
<nav class="tabs"><a class="tab-link <?= $kind==='categories'?'active':'' ?>" href="?type=categories">Categories</a><a class="tab-link <?= $kind==='provinces'?'active':'' ?>" href="?type=provinces">Provinces</a></nav>
<?php if ($error): ?><div class="panel" style="color:#B91C1C"><?= htmlspecialchars($error) ?></div><?php elseif (isset($_GET['saved'])): ?><div class="panel" style="color:#047857">Changes saved.</div><?php endif; ?>
<section class="panel"><h2><?= $editing ? 'Edit' : 'Add' ?> <?= htmlspecialchars(rtrim($config['title'], 's')) ?></h2><form method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>"><input type="hidden" name="type" value="<?= htmlspecialchars($kind) ?>"><input type="hidden" name="action" value="<?= $editing?'edit':'add' ?>"><input type="hidden" name="id" value="<?= (int)($editing[$config['id']] ?? 0) ?>"><label class="field-label">Name</label><input class="f-input" name="name" required maxlength="100" value="<?= htmlspecialchars($editing[$config['name']] ?? '') ?>"><?php if($kind==='categories'): ?><label class="field-label">English name</label><input class="f-input" name="name_en" maxlength="100" value="<?= htmlspecialchars($editing['category_name_en'] ?? '') ?>"><?php endif; ?><button class="btn" style="background:#10B981;color:#fff" type="submit"><?= $editing?'Save':'Add' ?></button><?php if($editing): ?> <a class="btn btn-outline" href="?type=<?= htmlspecialchars($kind) ?>">Cancel</a><?php endif; ?></form></section>
<section class="panel"><h2><?= htmlspecialchars($config['title']) ?></h2><?php if(!$rows): ?><div class="empty-state">No records.</div><?php else: ?><table class="data-table"><thead><tr><th>ID</th><th>Name</th><?php if($kind==='categories'): ?><th>English name</th><?php endif; ?><th>Actions</th></tr></thead><tbody><?php foreach($rows as $row): ?><tr><td><?= (int)$row[$config['id']] ?></td><td><?= htmlspecialchars($row[$config['name']]) ?></td><?php if($kind==='categories'): ?><td><?= htmlspecialchars($row['category_name_en'] ?? '') ?></td><?php endif; ?><td><a class="btn btn-outline" href="?type=<?= htmlspecialchars($kind) ?>&amp;edit=<?= (int)$row[$config['id']] ?>">Edit</a> <form method="post" style="display:inline" onsubmit="return confirm('Delete this item? It may be in use.')"><input type="hidden" name="csrf" value="<?= htmlspecialchars($csrf) ?>"><input type="hidden" name="type" value="<?= htmlspecialchars($kind) ?>"><input type="hidden" name="action" value="delete"><input type="hidden" name="id" value="<?= (int)$row[$config['id']] ?>"><button class="btn btn-danger">Delete</button></form></td></tr><?php endforeach; ?></tbody></table><?php endif; ?></section>
<?php require_once __DIR__ . '/admin_pagination.php'; admin_pagination($totalRows, $perPage, $page); ?></main></div></body></html>
