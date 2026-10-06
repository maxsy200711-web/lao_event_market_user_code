<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

// ---- ສະຫຼຸບຄະແນນ ແຍກຕາມແຕ່ລະງານ ----
$perPage = 10;
$summaryTotalStmt = $pdo->prepare('SELECT COUNT(*) FROM events WHERE organizer_id = ?');
$summaryTotalStmt->execute([$organizer_id]);
$summaryTotal = (int)$summaryTotalStmt->fetchColumn();
$summaryPage = max(1, (int)($_GET['summary_page'] ?? 1));
$summaryPage = min($summaryPage, max(1, (int)ceil($summaryTotal / $perPage)));
$summaryOffset = ($summaryPage - 1) * $perPage;
$stmt = $pdo->prepare("SELECT e.event_id, e.title,
                               COUNT(r.review_id) AS review_count,
                               ROUND(AVG(r.rating),1) AS avg_rating
                        FROM events e
                        LEFT JOIN reviews r ON r.event_id = e.event_id
                        WHERE e.organizer_id = ?
                        GROUP BY e.event_id, e.title
                        ORDER BY e.event_id DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $organizer_id, PDO::PARAM_INT);
$stmt->bindValue(2, $perPage, PDO::PARAM_INT);
$stmt->bindValue(3, $summaryOffset, PDO::PARAM_INT);
$stmt->execute();
$summary = $stmt->fetchAll();

// ---- ລິວິວລ້າສຸດ ພ້ອມຊື່ຜູ້ຂຽນ ແລະ ຊື່ງານ ----
$reviewTotalStmt = $pdo->prepare('SELECT COUNT(*) FROM reviews r JOIN events e ON e.event_id = r.event_id WHERE e.organizer_id = ?');
$reviewTotalStmt->execute([$organizer_id]);
$reviewTotal = (int)$reviewTotalStmt->fetchColumn();
$reviewsPage = max(1, (int)($_GET['reviews_page'] ?? 1));
$reviewsPage = min($reviewsPage, max(1, (int)ceil($reviewTotal / $perPage)));
$reviewsOffset = ($reviewsPage - 1) * $perPage;
$stmt = $pdo->prepare("SELECT r.rating, r.comment, r.create_at, u.full_name, e.title
                        FROM reviews r
                        JOIN events e ON e.event_id = r.event_id
                        JOIN users u ON u.user_id = r.user_id
                        WHERE e.organizer_id = ?
                        ORDER BY r.review_id DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $organizer_id, PDO::PARAM_INT);
$stmt->bindValue(2, $perPage, PDO::PARAM_INT);
$stmt->bindValue(3, $reviewsOffset, PDO::PARAM_INT);
$stmt->execute();
$reviews = $stmt->fetchAll();

function stars(?float $n): string {
    $n = (int) round($n ?? 0);
    return str_repeat('★', max(0,$n)) . str_repeat('☆', 5 - max(0,$n));
}
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ລາຍງານ ແລະ ລິວິວ | LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/organizer.css">
</head>
<body>
<?php include __DIR__ . "/organizer_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'reports'; include __DIR__ . "/organizer_sidebar.php"; ?>

    <div class="content">
        <p class="page-title">ລາຍງານ ແລະ ຄະແນນລິວິວ</p>

        <div class="panel">
            <h2>ຄະແນນສະເລ່ຍແຍກຕາມງານ</h2>
            <?php if (empty($summary)): ?>
                <div class="empty-state"><i class="ti ti-chart-bar-off"></i>ຍັງບໍ່ມີຂໍ້ມູນ</div>
            <?php else: ?>
            <table class="data-table">
                <tr><th>ຊື່ງານ</th><th>ຈຳນວນລິວິວ</th><th>ຄະແນນສະເລ່ຍ</th></tr>
                <?php foreach ($summary as $s): ?>
                <tr>
                    <td><?= htmlspecialchars($s['title']) ?></td>
                    <td><?= (int) $s['review_count'] ?></td>
                    <td><span class="stars"><?= stars($s['avg_rating']) ?></span> <?= $s['avg_rating'] ? $s['avg_rating'] : '-' ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
            <?php require_once __DIR__ . '/organizer_pagination.php'; organizer_pagination($summaryTotal, $perPage, $summaryPage, 'summary_page'); ?>
        </div>

        <div class="panel">
            <h2>ຄວາມຄິດເຫັນຫຼ້າສຸດ</h2>
            <?php if (empty($reviews)): ?>
                <div class="empty-state"><i class="ti ti-message-off"></i>ຍັງບໍ່ມີຄວາມຄິດເຫັນ</div>
            <?php else: ?>
            <table class="data-table">
                <tr><th>ຜູ້ໃຊ້</th><th>ງານ</th><th>ຄະແນນ</th><th>ຄວາມຄິດເຫັນ</th><th>ວັນທີ</th></tr>
                <?php foreach ($reviews as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['full_name']) ?></td>
                    <td><?= htmlspecialchars($r['title']) ?></td>
                    <td><span class="stars"><?= stars($r['rating']) ?></span></td>
                    <td><?= htmlspecialchars($r['comment']) ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['create_at']))) ?></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
            <?php require_once __DIR__ . '/organizer_pagination.php'; organizer_pagination($reviewTotal, $perPage, $reviewsPage, 'reviews_page'); ?>
        </div>
    </div>
</div>
<script src="assets/site-i18n.js"></script>
    </body>
</html>
