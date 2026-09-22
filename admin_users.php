<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";

// Process Verify / Unverify Actions
if (isset($_GET['action']) && $_GET['action'] === 'toggle_verify') {
    $org_id = (int)($_GET['id'] ?? 0);
    $status = (int)($_GET['status'] ?? 0);
    if ($org_id > 0) {
        $stmt = $pdo->prepare("UPDATE organizers SET is_verified = ? WHERE organizer_id = ?");
        $stmt->execute([$status, $org_id]);
    }
    header("Location: admin_users.php?tab=organizers");
    exit;
}

$tab = ($_GET['tab'] ?? 'users') === 'organizers' ? 'organizers' : 'users';

if ($tab === 'users') {
    $rows = $pdo->query("SELECT user_id AS id, full_name AS name, email, phone, create_at
                         FROM users ORDER BY user_id DESC")->fetchAll();
} else {
    // ດຶງຂໍ້ມູນ is_verified ເພີ່ມ
    $rows = $pdo->query("SELECT organizer_id AS id, organizer_name AS name, company_name, email, phone, is_verified
                         FROM organizers ORDER BY organizer_id DESC")->fetchAll();
}
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>ຈັດການຜູ້ໃຊ້ | Admin - LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/admin.css">
<style>
.badge-verified { color: #1D9BF0; font-size: 18px; vertical-align: middle; margin-left: 4px; }
.btn-verify { background: #1D9BF0; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; }
.btn-unverify { background: #64748B; color: #fff; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; }
</style>
</head>
<body>
<?php include __DIR__ . "/admin_topbar.php"; ?>
<div class="app-shell">
    <?php $active = 'users'; include __DIR__ . "/admin_sidebar.php"; ?>

    <div class="content">
        <p class="page-title" style="margin-bottom:16px">ຈັດການຜູ້ໃຊ້ທັງໝົດ</p>

        <?php if (isset($_GET['deleted'])): ?>
            <div style="background:#E1F5EE;color:#0F6E56;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px">ລຶບບັນຊີສຳເລັດແລ້ວ</div>
        <?php endif; ?>

        <div class="tabs">
            <a href="admin_users.php?tab=users" class="tab-link <?= $tab === 'users' ? 'active' : '' ?>">ຜູ້ໃຊ້ທົ່ວໄປ (User)</a>
            <a href="admin_users.php?tab=organizers" class="tab-link <?= $tab === 'organizers' ? 'active' : '' ?>">ຜູ້ຈັດງານ (Organizer)</a>
        </div>

        <div class="panel">
            <?php if (empty($rows)): ?>
                <div class="empty-state"><i class="ti ti-users"></i>ຍັງບໍ່ມີຂໍ້ມູນ</div>
            <?php elseif ($tab === 'users'): ?>
            <table class="data-table">
                <tr><th>ຊື່</th><th>ອີເມວ</th><th>ເບີໂທ</th><th>ວັນທີສະໝັກ</th><th></th></tr>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['name']) ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['phone'] ?: '-') ?></td>
                    <td><?= htmlspecialchars(date('d/m/Y', strtotime($r['create_at']))) ?></td>
                    <td>
                        <a href="admin_user_delete.php?type=user&id=<?= $r['id'] ?>" class="btn btn-danger"
                           onclick="return confirm('ຢືນຢັນການລຶບບັນຊີນີ້ບໍ?')">ລຶບ</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <?php else: ?>
            <table class="data-table">
                <tr><th>ຊື່ຜູ້ຈັດງານ</th><th>ບໍລິສັດ</th><th>ອີເມວ</th><th>ເບີໂທ</th><th>ສະຖານະ</th><th>ຈັດການ</th></tr>
                <?php foreach ($rows as $r): ?>
                <tr>
                    <td>
                        <?= htmlspecialchars($r['name']) ?>
                        <?php if (!empty($r['is_verified'])): ?>
                            <i class="ti ti-circle-check-filled badge-verified" title="ບັນຊີໄດ້ຮັບການຢືນຢັນແລ້ວ"></i>
                        <?php endif; ?>
                    </td>
                    <td><?= htmlspecialchars($r['company_name'] ?: '-') ?></td>
                    <td><?= htmlspecialchars($r['email']) ?></td>
                    <td><?= htmlspecialchars($r['phone'] ?: '-') ?></td>
                    <td>
                        <?php if ($r['is_verified']): ?>
                            <span style="color:#059669; font-weight:600; font-size:13px;">ຮັບຮອງແລ້ວ</span>
                        <?php else: ?>
                            <span style="color:#94A3B8; font-size:13px;">ທົ່ວໄປ</span>
                        <?php endif; ?>
                    </td>
                    <td style="display:flex; gap:8px;">
                        <?php if ($r['is_verified']): ?>
                            <a href="admin_users.php?action=toggle_verify&id=<?= $r['id'] ?>&status=0" class="btn-unverify" onclick="return confirm('ຍົກເລີກການຮັບຮອງບໍ?')">ຍົກເລີກຮັບຮອງ</a>
                        <?php else: ?>
                            <a href="admin_users.php?action=toggle_verify&id=<?= $r['id'] ?>&status=1" class="btn-verify" onclick="return confirm('ຢືນຢັນການຮັບຮອງ Organizer ນີ້ບໍ?')">ຢືນຢັນຕົວຕົນ</a>
                        <?php endif; ?>
                        <a href="admin_user_delete.php?type=organizer&id=<?= $r['id'] ?>" class="btn btn-danger"
                           onclick="return confirm('ຢືນຢັນການລຶບບັນຊີນີ້ບໍ? (ງານທັງໝົດຂອງລາວຈະຖືກລຶບນຳ)')">ລຶບ</a>
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