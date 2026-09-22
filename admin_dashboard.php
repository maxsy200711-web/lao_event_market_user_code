<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";

$total_users      = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_organizers = $pdo->query("SELECT COUNT(*) FROM organizers")->fetchColumn();
$total_events     = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();
$total_reviews    = $pdo->query("SELECT COUNT(*) FROM reviews")->fetchColumn();
$pending_count    = $pdo->query("SELECT COUNT(*) FROM events WHERE status='pending'")->fetchColumn();
$approved_count   = $pdo->query("SELECT COUNT(*) FROM events WHERE status='approved'")->fetchColumn();

// ງານທີ່ອະນຸມັດຫຼ້າສຸດ 5 ລາຍການ
$recent = $pdo->query("SELECT e.title, e.status, e.start_date, o.organizer_name
                        FROM events e JOIN organizers o ON o.organizer_id = e.organizer_id
                        ORDER BY e.event_id DESC LIMIT 5")->fetchAll();

$badge = ['pending' => 'badge-pending', 'approved' => 'badge-approved', 'rejected' => 'badge-rejected'];
$label = ['pending' => 'ລໍຖ້າ', 'approved' => 'ອະນຸມັດແລ້ວ', 'rejected' => 'ປະຕິເສດ'];
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ພາບລວມລະບົບ | Admin - LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<?php include __DIR__ . "/admin_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'overview'; include __DIR__ . "/admin_sidebar.php"; ?>

    <div class="content">
        <p class="page-title" style="margin-bottom:16px">ພາບລວມລະບົບ</p>

        <div class="stat-grid">
            <div class="stat-card"><p class="label">User ທັງໝົດ</p><p class="value"><?= number_format($total_users) ?></p></div>
            <div class="stat-card"><p class="label">Organizer ທັງໝົດ</p><p class="value"><?= number_format($total_organizers) ?></p></div>
            <div class="stat-card"><p class="label">ງານຕະຫຼາດນັດທັງໝົດ</p><p class="value"><?= number_format($total_events) ?></p></div>
            <div class="stat-card"><p class="label">ລິວິວທັງໝົດ</p><p class="value"><?= number_format($total_reviews) ?></p></div>
            <div class="stat-card"><p class="label">ລໍຖ້າອະນຸມັດ</p><p class="value" style="color:#854F0B"><?= number_format($pending_count) ?></p></div>
            <div class="stat-card"><p class="label">ອະນຸມັດແລ້ວ</p><p class="value" style="color:#0F6E56"><?= number_format($approved_count) ?></p></div>
        </div>

        <div class="panel">
            <h2>ງານທີ່ເພີ່ມເຂົ້າລ່າສຸດ</h2>
            <?php if (empty($recent)): ?>
                <div class="empty-state"><i class="ti ti-calendar-off"></i>ຍັງບໍ່ມີຂໍ້ມູນ</div>
            <?php else: ?>
            <table class="data-table">
                <tr><th>ຊື່ງານ</th><th>ຜູ້ຈັດງານ</th><th>ວັນທີ</th><th>ສະຖານະ</th></tr>
                <?php foreach ($recent as $ev): ?>
                <tr>
                    <td><?= htmlspecialchars($ev['title']) ?></td>
                    <td><?= htmlspecialchars($ev['organizer_name']) ?></td>
                    <td><?= htmlspecialchars($ev['start_date']) ?></td>
                    <td><span class="badge <?= $badge[$ev['status']] ?>"><?= $label[$ev['status']] ?></span></td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>
