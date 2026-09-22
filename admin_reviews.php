<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";

$reviews = $pdo->query("SELECT r.review_id, r.rating, r.comment, r.create_at,
                                u.full_name, e.title AS event_title
                         FROM reviews r
                         JOIN users u ON u.user_id = r.user_id
                         JOIN events e ON e.event_id = r.event_id
                         ORDER BY r.review_id DESC")->fetchAll();

function stars(int $n): string { return str_repeat('★', $n) . str_repeat('☆', 5 - $n); }
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ຈັດການລິວິວ | Admin - LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<?php include __DIR__ . "/admin_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'reviews'; include __DIR__ . "/admin_sidebar.php"; ?>

    <div class="content">
        <p class="page-title" style="margin-bottom:16px">ຈັດການລິວິວ ແລະ ຄະແນນ</p>

        <?php if (isset($_GET['deleted'])): ?>
            <div style="background:#E1F5EE;color:#0F6E56;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px">ລຶບຄວາມຄິດເຫັນສຳເລັດແລ້ວ</div>
        <?php endif; ?>

        <div class="panel">
            <?php if (empty($reviews)): ?>
                <div class="empty-state"><i class="ti ti-message-off"></i>ຍັງບໍ່ມີລິວິວໃນລະບົບ</div>
            <?php else: ?>
            <table class="data-table">
                <tr><th>ຜູ້ໃຊ້</th><th>ງານ</th><th>ຄະແນນ</th><th>ຄວາມຄິດເຫັນ</th><th>ວັນທີ</th><th></th></tr>
                <?php foreach ($reviews as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['full_name']) ?></td>
                    <td><?= htmlspecialchars($r['event_title']) ?></td>
                    <td><span class="stars"><?= stars((int) $r['rating']) ?></span></td>
                    <td style="max-width:260px;white-space:normal"><?= htmlspecialchars($r['comment']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['create_at']))) ?></td>
                    <td>
                        <a href="admin_review_delete.php?id=<?= $r['review_id'] ?>" class="btn btn-danger"
                           onclick="return confirm('ຢືນຢັນການລຶບຄວາມຄິດເຫັນນີ້ບໍ?')">ລຶບ</a>
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
