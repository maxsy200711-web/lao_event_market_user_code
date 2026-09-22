<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

function img_path(?string $p): string {
    if (!$p) return '';
    return preg_match('/^https?:\/\//i', $p) ? $p : 'uploads/' . ltrim($p, '/');
}

// ---- ລາຍການງານທັງໝົດຂອງ Organizer ຄົນນີ້ ສຳລັບ dropdown ----
$stmt = $pdo->prepare("SELECT event_id, title FROM events WHERE organizer_id = ? ORDER BY event_id DESC");
$stmt->execute([$organizer_id]);
$my_events = $stmt->fetchAll();

$selected_event = isset($_GET['event_id']) ? (int) $_GET['event_id'] : ($my_events[0]['event_id'] ?? 0);

// ---- ຢືນຢັນວ່າ event ນີ້ເປັນຂອງ organizer ຄົນນີ້ແທ້ ----
$owns_event = false;
foreach ($my_events as $e) { if ($e['event_id'] == $selected_event) { $owns_event = true; break; } }

$stalls = [];
if ($owns_event && $selected_event) {
    $stmt = $pdo->prepare("SELECT * FROM event_stalls WHERE event_id = ? ORDER BY stall_id DESC");
    $stmt->execute([$selected_event]);
    $stalls = $stmt->fetchAll();
}
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ຈັດການຮ້ານຄ້າໃນງານ | LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/organizer.css">
</head>
<body>
<?php include __DIR__ . "/organizer_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'stalls'; include __DIR__ . "/organizer_sidebar.php"; ?>

    <div class="content">
        <p class="page-title">ຈັດການຮ້ານຄ້າໃນງານ</p>

        <?php if (isset($_GET['saved'])): ?><div class="alert alert-success">ບັນທຶກຮ້ານຄ້າສຳເລັດແລ້ວ</div><?php endif; ?>
        <?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">ລຶບຮ້ານຄ້າສຳເລັດແລ້ວ</div><?php endif; ?>

        <?php if (empty($my_events)): ?>
            <div class="panel"><div class="empty-state"><i class="ti ti-calendar-off"></i>ທ່ານຕ້ອງສ້າງງານຕະຫຼາດນັດກ່ອນ ຈຶ່ງຈະເພີ່ມຮ້ານຄ້າໄດ້. <a href="organizer_event_form.php" style="color:#10B981">ເພີ່ມງານ →</a></div></div>
        <?php else: ?>

        <div class="panel">
            <form method="GET" style="display:flex;gap:10px;align-items:end;max-width:420px">
                <div style="flex:1">
                    <label class="field-label">ເລືອກງານຕະຫຼາດນັດ</label>
                    <select class="f-select" name="event_id" onchange="this.form.submit()" style="margin-bottom:0">
                        <?php foreach ($my_events as $e): ?>
                        <option value="<?= $e['event_id'] ?>" <?= $e['event_id'] == $selected_event ? 'selected' : '' ?>>
                            <?= htmlspecialchars($e['title']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
        </div>

        <div class="panel">
            <h2>ເພີ່ມຮ້ານຄ້າ</h2>
            <form action="organizer_stall_save.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="event_id" value="<?= $selected_event ?>">
                <input type="hidden" name="stall_id" value="0">
                <div class="form-row">
                    <div>
                        <label class="field-label">ຊື່ຮ້ານ</label>
                        <input class="f-input" type="text" name="stall_name" required>
                    </div>
                    <div>
                        <label class="field-label">ເລກບູດ</label>
                        <input class="f-input" type="text" name="stall_number" placeholder="ຕົວຢ່າງ: A01">
                    </div>
                    <div>
                        <label class="field-label">ປະເພດສິນຄ້າ</label>
                        <input class="f-input" type="text" name="categories" placeholder="ຕົວຢ່າງ: ອາຫານ, ເຄື່ອງນຸ່ງ">
                    </div>
                    <div>
                        <label class="field-label">ຊ່ອງທາງຕິດຕໍ່</label>
                        <input class="f-input" type="text" name="contact_info" placeholder="ເບີໂທ / Facebook">
                    </div>
                </div>
                <label class="field-label">ລາຍລະອຽດ</label>
                <textarea class="f-textarea" name="description"></textarea>
                <label class="field-label">ຮູບຮ້ານ</label>
                <input class="f-input" type="file" name="image" accept="image/*">
                <button type="submit" class="btn btn-green"><i class="ti ti-plus"></i> ເພີ່ມຮ້ານຄ້າ</button>
            </form>
        </div>

        <div class="panel">
            <h2>ຮ້ານຄ້າໃນງານນີ້ (<?= count($stalls) ?>)</h2>
            <?php if (empty($stalls)): ?>
                <div class="empty-state"><i class="ti ti-building-store"></i>ຍັງບໍ່ມີຮ້ານຄ້າໃນງານນີ້</div>
            <?php else: ?>
            <table class="data-table">
                <tr><th></th><th>ຊື່ຮ້ານ</th><th>ເລກບູດ</th><th>ປະເພດ</th><th>ຕິດຕໍ່</th><th>ຈັດການ</th></tr>
                <?php foreach ($stalls as $s): ?>
                <tr>
                    <td>
                        <?php if ($s['image']): ?>
                            <img class="thumb" src="<?= htmlspecialchars(img_path($s['image'])) ?>" alt="">
                        <?php else: ?>
                            <div class="thumb" style="display:flex;align-items:center;justify-content:center;color:#94A3B8"><i class="ti ti-building-store"></i></div>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($s['stall_name']) ?></td>
                    <td><?= htmlspecialchars($s['stall_number']) ?></td>
                    <td><?= htmlspecialchars($s['categories']) ?></td>
                    <td><?= htmlspecialchars($s['contact_info']) ?></td>
                    <td>
                        <a href="organizer_stall_delete.php?id=<?= $s['stall_id'] ?>&event_id=<?= $selected_event ?>"
                           class="btn btn-danger btn-sm"
                           onclick="return confirm('ຢືນຢັນການລຶບຮ້ານຄ້ານີ້ບໍ?')">ລຶບ</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
