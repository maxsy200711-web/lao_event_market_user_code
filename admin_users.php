<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth_admin.php';
$active = 'users';
$accountType = ($_GET['type'] ?? 'users') === 'admins' ? 'admins' : 'users';
$where = $accountType === 'admins' ? "role_id = 'ADMIN'" : "role_id NOT IN ('ORGANIZER','ADMIN')";
$perPage = 10;
$page = max(1, (int)($_GET['page'] ?? 1));
$totalRows = (int)$pdo->query("SELECT COUNT(*) FROM users WHERE $where")->fetchColumn();
$page = min($page, max(1, (int)ceil($totalRows / $perPage)));
$offset = ($page - 1) * $perPage;
$rows = $pdo->query("SELECT user_id, username, full_name, email, phone, role_id, is_verified, create_at FROM users WHERE $where ORDER BY user_id DESC LIMIT $perPage OFFSET $offset")->fetchAll();
?>
<!doctype html>
<html lang="lo"><head>
    <meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Manage Users | LAOeventMarket Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/admin.css?v=20261002-pagination">
</head><body>
<?php include __DIR__ . '/admin_topbar.php'; ?>
<div class="app-shell">
    <?php include __DIR__ . '/admin_sidebar.php'; ?>
    <main class="content">
        <p class="page-title" style="margin-bottom:16px">ຈັດການບັນຊີ</p>
        <nav class="tabs"><a class="tab-link <?= $accountType === 'users' ? 'active' : '' ?>" href="admin_users.php?type=users">ຜູ້ໃຊ້ທົ່ວໄປ</a><a class="tab-link <?= $accountType === 'admins' ? 'active' : '' ?>" href="admin_users.php?type=admins">ຜູ້ດູແລລະບົບ</a></nav>
        <?php if (isset($_GET['saved'])): ?><div class="panel" style="color:#047857">ບັນທຶກຂໍ້ມູນສຳເລັດ</div><?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?><div class="panel" style="color:#047857">ລຶບບັນຊີສຳເລັດ</div><?php endif; ?>
        <?php if (($_GET['error'] ?? '') === 'self_delete'): ?><div class="panel" style="color:#B91C1C">ບໍ່ສາມາດລຶບບັນຊີທີ່ກຳລັງໃຊ້ງານຢູ່ໄດ້</div><?php endif; ?>
        <?php if (($_GET['error'] ?? '') === 'delete_failed'): ?><div class="panel" style="color:#B91C1C">ລຶບບັນຊີບໍ່ສຳເລັດ ເນື່ອງຈາກຍັງມີຂໍ້ມູນອື່ນອ້າງອີງຢູ່</div><?php endif; ?>
        <section class="panel">
            <p style="margin-bottom:14px"><a href="admin_user_form.php?type=<?= htmlspecialchars($accountType) ?>" class="btn" style="background:#10B981;color:#fff">+ ເພີ່ມ<?= $accountType === 'admins' ? 'ຜູ້ດູແລ' : 'ຜູ້ໃຊ້' ?></a></p>
            <?php if (!$rows): ?>
                <div class="empty-state"><i class="ti ti-users"></i>ບໍ່ພົບບັນຊີ</div>
            <?php else: ?>
                <table class="data-table"><thead><tr><th>ຊື່ຜູ້ໃຊ້</th><th>ຊື່ເຕັມ</th><th>ອີເມວ</th><th>ເບີໂທ</th><th>ສິດຜູ້ໃຊ້</th><th>ວັນທີສ້າງ</th><th>ຈັດການ</th></tr></thead><tbody>
                    <?php foreach ($rows as $row): $canDelete = !($row['role_id'] === 'ADMIN' && ($_SESSION['admin_source'] ?? '') !== 'admins' && (int)$row['user_id'] === (int)$admin_id); ?><tr>
                        <td><?= htmlspecialchars($row['username'] ?? '') ?></td>
                        <td><?= htmlspecialchars($row['full_name']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['phone'] ?: '-') ?></td>
                        <td><span class="badge badge-role"><?= $row['role_id'] === 'ADMIN' ? 'ຜູ້ດູແລລະບົບ' : 'ຜູ້ໃຊ້' ?></span></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($row['create_at']))) ?></td>
                        <td>
                            <a href="admin_user_form.php?id=<?= (int)$row['user_id'] ?>&amp;type=<?= htmlspecialchars($accountType) ?>" class="btn btn-outline">ແກ້ໄຂ</a>
                            <?php if ($canDelete): ?><a href="admin_user_delete.php?type=user&id=<?= (int)$row['user_id'] ?>" class="btn btn-danger" onclick="return confirm('ຢືນຢັນການລຶບບັນຊີນີ້?')">ລຶບ</a><?php endif; ?>
                        </td>
                    </tr><?php endforeach; ?>
                </tbody></table>
                <?php require_once __DIR__ . '/admin_pagination.php'; admin_pagination($totalRows, $perPage, $page); ?>
            <?php endif; ?>
        </section>
    </main>
</div>
<script src="assets/site-i18n.js?v=20260930-admin-locale"></script>
    </body></html>
