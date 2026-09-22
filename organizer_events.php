<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

function img_path(?string $p): string {
    if (!$p) return '';
    return preg_match('/^https?:\/\//i', $p) ? $p : 'uploads/' . ltrim($p, '/');
}

$stmt = $pdo->prepare("SELECT e.*, p.province_name, c.category_name
                        FROM events e
                        JOIN provinces p ON p.province_id = e.province_id
                        JOIN categories c ON c.category_id = e.category_id
                        WHERE e.organizer_id = ?
                        ORDER BY e.event_id DESC");
$stmt->execute([$organizer_id]);
$events = $stmt->fetchAll();

$badge = ['pending' => 'badge-pending', 'approved' => 'badge-approved', 'rejected' => 'badge-rejected'];
$label = ['pending' => 'ລໍຖ້າອະນຸມັດ', 'approved' => 'ອະນຸມັດແລ້ວ', 'rejected' => 'ປະຕິເສດ'];
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ຈັດການງານຕະຫຼາດນັດ | LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/organizer.css">
</head>
<body>
<?php include __DIR__ . "/organizer_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'events'; include __DIR__ . "/organizer_sidebar.php"; ?>

    <div class="content">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:16px">
            <p class="page-title" style="margin:0">ຈັດການງານຕະຫຼາດນັດຂອງຂ້ອຍ</p>
            <a href="organizer_event_form.php" class="btn btn-green"><i class="ti ti-plus"></i> ເພີ່ມງານໃໝ່</a>
        </div>

        <?php if (isset($_GET['saved'])): ?><div class="alert alert-success">ບັນທຶກງານສຳເລັດແລ້ວ</div><?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">ລຶບງານສຳເລັດແລ້ວ</div><?php endif; ?>

        <div class="panel">
            <?php if (empty($events)): ?>
                <div class="empty-state"><i class="ti ti-calendar-off"></i>ທ່ານຍັງບໍ່ມີງານຕະຫຼາດນັດ</div>
            <?php else: ?>
            <table class="data-table">
                <tr><th></th><th>ຊື່ງານ</th><th>ໝວດໝູ່</th><th>ແຂວງ</th><th>ວັນທີ</th><th>ສະຖານະ</th><th>ຈັດການ</th></tr>
                <?php foreach ($events as $ev): ?>
                <tr>
                    <td>
                        <?php if ($ev['banner_img']): ?>
                            <img class="thumb" src="<?= htmlspecialchars(img_path($ev['banner_img'])) ?>" alt="">
                        <?php else: ?>
                            <div class="thumb" style="display:flex;align-items:center;justify-content:center;color:#94A3B8"><i class="ti ti-photo"></i></div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($ev['title']) ?></td>
                    <td><?= htmlspecialchars($ev['category_name']) ?></td>
                    <td><?= htmlspecialchars($ev['province_name']) ?></td>
                    <td><?= htmlspecialchars($ev['start_date']) ?> → <?= htmlspecialchars($ev['end_date']) ?></td>
                    <td><span class="badge <?= $badge[$ev['status']] ?>"><?= $label[$ev['status']] ?></span></td>
                    <td style="white-space:nowrap">
                        <a href="organizer_event_form.php?id=<?= $ev['event_id'] ?>" class="btn btn-outline btn-sm">ແກ້ໄຂ</a>
                        <a href="organizer_event_delete.php?id=<?= $ev['event_id'] ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('ຢືນຢັນການລຶບງານນີ້ບໍ?')">ລຶບ</a>
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
