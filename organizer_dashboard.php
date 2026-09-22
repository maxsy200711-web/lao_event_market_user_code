<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

// ---- ນັບສະຖິຕິງານຂອງຜູ້ຈັດງານຄົນນີ້ ----
$stmt = $pdo->prepare("SELECT status, COUNT(*) AS total FROM events WHERE organizer_id = ? GROUP BY status");
$stmt->execute([$organizer_id]);
$counts = ['pending' => 0, 'approved' => 0, 'rejected' => 0];
foreach ($stmt->fetchAll() as $row) {
    $counts[$row['status']] = (int) $row['total'];
}
$total_events = array_sum($counts);

// ---- ງານລ່າສຸດ 5 ລາຍການ ----
$stmt = $pdo->prepare("SELECT e.event_id, e.title, e.start_date, e.end_date, e.status, p.province_name
                        FROM events e
                        JOIN provinces p ON p.province_id = e.province_id
                        WHERE e.organizer_id = ?
                        ORDER BY e.event_id DESC LIMIT 5");
$stmt->execute([$organizer_id]);
$recent = $stmt->fetchAll();

$badge = ['pending' => 'badge-pending', 'approved' => 'badge-approved', 'rejected' => 'badge-rejected'];
$label = ['pending' => 'ລໍຖ້າອະນຸມັດ', 'approved' => 'ອະນຸມັດແລ້ວ', 'rejected' => 'ປະຕິເສດ'];
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ພາບລວມ - ຜູ້ຈັດງານ | LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/organizer.css">
</head>
<body>
<?php include __DIR__ . "/organizer_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'dashboard'; include __DIR__ . "/organizer_sidebar.php"; ?>

    <div class="content">
        <p class="page-title">ພາບລວມ</p>

        <div class="stat-grid">
            <div class="stat-card"><p class="label">ງານທັງໝົດ</p><p class="value"><?= $total_events ?></p></div>
            <div class="stat-card green"><p class="label">ອະນຸມັດແລ້ວ</p><p class="value"><?= $counts['approved'] ?></p></div>
            <div class="stat-card amber"><p class="label">ລໍຖ້າອະນຸມັດ</p><p class="value"><?= $counts['pending'] ?></p></div>
        </div>

        <div class="panel">
            <h2>ງານຕະຫຼາດນັດລ່າສຸດ</h2>
            <?php if (empty($recent)): ?>
                <div class="empty-state"><i class="ti ti-calendar-off"></i>ທ່ານຍັງບໍ່ມີງານຕະຫຼາດນັດ. <a href="organizer_event_form.php" style="color:#10B981">ເພີ່ມງານທຳອິດ →</a></div>
            <?php else: ?>
            <table class="data-table">
                <tr><th>ຊື່ງານ</th><th>ແຂວງ</th><th>ວັນທີ</th><th>ສະຖານະ</th><th></th></tr>
                <?php foreach ($recent as $ev): ?>
                <tr>
                    <td><?= htmlspecialchars($ev['title']) ?></td>
                    <td><?= htmlspecialchars($ev['province_name']) ?></td>
                    <td><?= htmlspecialchars($ev['start_date']) ?> → <?= htmlspecialchars($ev['end_date']) ?></td>
                    <td><span class="badge <?= $badge[$ev['status']] ?>"><?= $label[$ev['status']] ?></span></td>
                    <td><a href="organizer_event_form.php?id=<?= $ev['event_id'] ?>" class="btn btn-outline btn-sm">ແກ້ໄຂ</a></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
