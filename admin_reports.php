<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";

$by_status = $pdo->query("SELECT status, COUNT(*) AS total FROM events GROUP BY status")->fetchAll(PDO::FETCH_KEY_PAIR);
$by_category = $pdo->query("SELECT c.category_name, COUNT(e.event_id) AS total
                             FROM categories c LEFT JOIN events e ON e.category_id = c.category_id
                             GROUP BY c.category_id, c.category_name ORDER BY total DESC")->fetchAll();
$by_province = $pdo->query("SELECT p.province_name, COUNT(e.event_id) AS total
                             FROM provinces p LEFT JOIN events e ON e.province_id = p.province_id
                             GROUP BY p.province_id, p.province_name ORDER BY total DESC")->fetchAll();
$top_rated = $pdo->query("SELECT e.title, ROUND(AVG(r.rating),1) AS avg_rating, COUNT(r.review_id) AS review_count
                           FROM events e JOIN reviews r ON r.event_id = e.event_id
                           GROUP BY e.event_id, e.title
                           HAVING COUNT(r.review_id) > 0
                           ORDER BY avg_rating DESC, review_count DESC LIMIT 5")->fetchAll();

$total_events = array_sum($by_status);
function pct($n, $total) { return $total > 0 ? round($n / $total * 100) : 0; }
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ລາຍງານສະຖິຕິ | Admin - LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<?php include __DIR__ . "/admin_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'reports'; include __DIR__ . "/admin_sidebar.php"; ?>

    <div class="content">
        <p class="page-title" style="margin-bottom:16px">ລາຍງານສະຖິຕິ</p>

        <div class="stat-grid">
            <div class="stat-card"><p class="label">ງານທັງໝົດ</p><p class="value"><?= $total_events ?></p></div>
            <div class="stat-card"><p class="label">ອະນຸມັດແລ້ວ</p><p class="value"><?= $by_status['approved'] ?? 0 ?> (<?= pct($by_status['approved'] ?? 0, $total_events) ?>%)</p></div>
            <div class="stat-card"><p class="label">ລໍຖ້າ / ປະຕິເສດ</p><p class="value"><?= ($by_status['pending'] ?? 0) . ' / ' . ($by_status['rejected'] ?? 0) ?></p></div>
        </div>

        <div class="panel">
            <h2>ຈຳນວນງານແຍກຕາມໝວດໝູ່</h2>
            <?php if (empty($by_category)): ?><div class="empty-state">ບໍ່ມີຂໍ້ມູນ</div><?php else: ?>
            <table class="data-table">
                <tr><th>ໝວດໝູ່</th><th>ຈຳນວນງານ</th></tr>
                <?php foreach ($by_category as $c): ?>
                <tr><td><?= htmlspecialchars($c['category_name']) ?></td><td><?= (int) $c['total'] ?></td></tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>

        <div class="panel">
            <h2>ຈຳນວນງານແຍກຕາມແຂວງ</h2>
            <?php if (empty($by_province)): ?><div class="empty-state">ບໍ່ມີຂໍ້ມູນ</div><?php else: ?>
            <table class="data-table">
                <tr><th>ແຂວງ</th><th>ຈຳນວນງານ</th></tr>
                <?php foreach ($by_province as $p): ?>
                <tr><td><?= htmlspecialchars($p['province_name']) ?></td><td><?= (int) $p['total'] ?></td></tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>

        <div class="panel">
            <h2>ງານທີ່ໄດ້ຄະແນນສູງສຸດ</h2>
            <?php if (empty($top_rated)): ?><div class="empty-state">ຍັງບໍ່ມີລິວິວພຽງພໍ</div><?php else: ?>
            <table class="data-table">
                <tr><th>ຊື່ງານ</th><th>ຄະແນນສະເລ່ຍ</th><th>ຈຳນວນລິວິວ</th></tr>
                <?php foreach ($top_rated as $t): ?>
                <tr><td><?= htmlspecialchars($t['title']) ?></td><td><span class="stars"><?= str_repeat('★', (int)round($t['avg_rating'])) ?></span> <?= $t['avg_rating'] ?></td><td><?= (int) $t['review_count'] ?></td></tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
