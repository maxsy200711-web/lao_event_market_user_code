<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";

// -------------------------------------------------------------
// 1. ປະມວນຜົນການລົບງານ (Delete Event Handler)
// -------------------------------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $delete_id = (int)$_GET['id'];
    
    // ລົບຂໍ້ມູນງານຈາກຖານຂໍ້ມູນ
    $stmt = $pdo->prepare("DELETE FROM events WHERE event_id = ?");
    if ($stmt->execute([$delete_id])) {
        header("Location: admin_approvals.php?deleted=1");
        exit;
    }
}

// -------------------------------------------------------------
// 2. ດຶງຂໍ້ມູນສະຖິຕິ ແລະ ລາຍການງານ
// -------------------------------------------------------------
$total_users      = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();
$total_organizers = $pdo->query("SELECT COUNT(*) FROM organizers")->fetchColumn();
$total_events     = $pdo->query("SELECT COUNT(*) FROM events")->fetchColumn();

// SELECT ດຶງ e.end_date ມາພ້ອມ
$events = $pdo->query("SELECT e.event_id, e.title, e.start_date, e.end_date, e.location, e.status, o.organizer_name, o.company_name
                        FROM events e JOIN organizers o ON o.organizer_id = e.organizer_id
                        ORDER BY (e.status='pending') DESC, e.event_id DESC")->fetchAll();

$badge = ['pending' => 'badge-pending', 'approved' => 'badge-approved', 'rejected' => 'badge-rejected'];
$label = ['pending' => 'ລໍຖ້າ', 'approved' => 'ອະນຸມັດແລ້ວ', 'rejected' => 'ປະຕິເສດ'];
?>
<!doctype html>
<html lang="lo">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ອະນຸມັດງານຕະຫຼາດນັດ | Admin - LAOeventMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/admin.css">
    
    <style>
        /* CSS ສຳລັບ Modal Popup */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-content {
            background: #fff;
            border-radius: 12px;
            width: 90%;
            max-width: 750px;
            max-height: 85vh;
            overflow-y: auto;
            position: relative;
            padding: 24px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            animation: modalFadeIn 0.2s ease-in-out;
        }
        @keyframes modalFadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .modal-close {
            position: absolute;
            top: 16px;
            right: 16px;
            background: #F1F5F9;
            border: none;
            border-radius: 50%;
            width: 36px;
            height: 36px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: #64748B;
            transition: all 0.2s;
        }
        .modal-close:hover {
            background: #E2E8F0;
            color: #0F172A;
        }

        /* ປຸ່ມລົບ */
        .icon-btn.icon-delete {
            background-color: #FEE2E2;
            color: #EF4444;
        }
        .icon-btn.icon-delete:hover {
            background-color: #EF4444;
            color: #FFFFFF;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . "/admin_topbar.php"; ?>
    <div class="app-shell">
        <?php $active = 'approvals';
        include __DIR__ . "/admin_sidebar.php"; ?>

        <div class="content">
            <p class="page-title" style="margin-bottom:16px">ອະນຸມັດ ແລະ ຈັດການງານຕະຫຼາດນັດ</p>

            <?php if (isset($_GET['done'])): ?>
                <div style="background:#E1F5EE;color:#0F6E56;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px">ອັບເດດສະຖານະງານສຳເລັດແລ້ວ</div>
            <?php endif; ?>

            <?php if (isset($_GET['deleted'])): ?>
                <div style="background:#FEE2E2;color:#991B1B;padding:10px 14px;border-radius:8px;font-size:13px;margin-bottom:16px">ລົບງານຕະຫຼາດນັດອອກຈາກລະບົບສຳເລັດແລ້ວ</div>
            <?php endif; ?>

            <div class="stat-grid">
                <div class="stat-card">
                    <p class="label">User ທັງໝົດ</p>
                    <p class="value"><?= number_format($total_users) ?></p>
                </div>
                <div class="stat-card">
                    <p class="label">Organizer</p>
                    <p class="value"><?= number_format($total_organizers) ?></p>
                </div>
                <div class="stat-card">
                    <p class="label">ງານທັງໝົດ</p>
                    <p class="value"><?= number_format($total_events) ?></p>
                </div>
            </div>

            <div class="panel">
                <?php if (empty($events)): ?>
                    <div class="empty-state"><i class="ti ti-calendar-off"></i>ຍັງບໍ່ມີງານໃນລະບົບ</div>
                <?php else: ?>
                    <table class="data-table">
                        <tr>
                            <th>ຊື່ງານ</th>
                            <th>ຜູ້ຈັດງານ</th>
                            <th>ໄລຍະເວລາຈັດງານ</th>
                            <th>ສະຖານທີ່</th>
                            <th>ສະຖານະ</th>
                            <th>ຈັດການ</th>
                        </tr>
                        <?php foreach ($events as $ev): ?>
                            <tr>
                                <td>
                                    <a href="javascript:void(0)" onclick="openDetailModal(<?= $ev['event_id'] ?>)" style="color: #059669; font-weight: 600; text-decoration: none;">
                                        <?= htmlspecialchars($ev['title']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($ev['company_name'] ?: $ev['organizer_name']) ?></td>
                                <td>
                                    <?= htmlspecialchars($ev['start_date']) ?>
                                    <?php if (!empty($ev['end_date'])): ?>
                                        <br><span style="font-size: 12px; color: #64748B;">ຫາ <?= htmlspecialchars($ev['end_date']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?= htmlspecialchars($ev['location']) ?></td>
                                <td><span class="badge <?= $badge[$ev['status']] ?>"><?= $label[$ev['status']] ?></span></td>
                                <td>
                                    <div style="display: flex; gap: 4px;">
                                        <?php if ($ev['status'] === 'pending'): ?>
                                            <a href="admin_event_approve.php?id=<?= $ev['event_id'] ?>&action=approve"
                                                class="icon-btn icon-approve" title="ອະນຸມັດ"><i class="ti ti-check"></i></a>
                                            <a href="admin_event_approve.php?id=<?= $ev['event_id'] ?>&action=reject"
                                                class="icon-btn icon-reject" title="ປະຕິເສດ"
                                                onclick="return confirm('ຢືນຢັນການປະຕິເສດງານນີ້ບໍ?')"><i class="ti ti-x"></i></a>
                                        <?php endif; ?>
                                        
                                        <!-- ປຸ່ມລົບງານ -->
                                        <a href="?action=delete&id=<?= $ev['event_id'] ?>" 
                                           class="icon-btn icon-delete" 
                                           title="ລົບງານ" 
                                           onclick="return confirm('ທ່ານຕ້ອງການລົບງານນີ້ອອກຈາກລະບົບແທ້ບໍ? (ຂໍ້ມູນທີ່ກ່ຽວຂ້ອງຈະຖືກລົບອອກທັງໝົດ)')">
                                            <i class="ti ti-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- HTML Structure ສຳລັບ Modal Popup -->
    <div id="detailModal" class="modal-overlay" onclick="closeDetailModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <button type="button" class="modal-close" onclick="closeDetailModal()"><i class="ti ti-x"></i></button>
            <div id="modalBody">
                <div style="text-align: center; padding: 40px; color: #64748B;">
                    <p>ກຳລັງໂຫລດຂໍ້ມູນ...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
    function openDetailModal(eventId) {
        const modal = document.getElementById('detailModal');
        const modalBody = document.getElementById('modalBody');
        
        modal.classList.add('active');
        modalBody.innerHTML = `
            <div style="text-align: center; padding: 40px; color: #64748B;">
                <p>ກຳລັງໂຫລດຂໍ້ມູນ...</p>
            </div>
        `;

        fetch('admin_event_detail.php?id=' + eventId + '&ajax=1')
            .then(response => response.text())
            .then(data => {
                modalBody.innerHTML = data;
            })
            .catch(err => {
                modalBody.innerHTML = '<p style="color:red; text-align:center;">ເກີດຂໍ້ຜິດພາດໃນການໂຫລດຂໍ້ມູນ</p>';
            });
    }

    function closeDetailModal(e) {
        document.getElementById('detailModal').classList.remove('active');
    }
    </script>
</body>

</html>