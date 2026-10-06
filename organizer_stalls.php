<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

function img_path(?string $p): string
{
    if (!$p) return '';
    return preg_match('/^https?:\/\//i', $p) ? $p : 'uploads/' . ltrim($p, '/');
}

// ---- ລາຍການງານທັງໝົດຂອງ Organizer ຄົນນີ້ ສຳລັບ dropdown ----
$stmt =$pdo->prepare("SELECT event_id, title FROM events WHERE organizer_id = ? ORDER BY event_id DESC");
$stmt->execute([$organizer_id]);
$my_events =$stmt->fetchAll();

$selected_event = isset($_GET['event_id']) ? (int) $_GET['event_id'] : ($my_events[0]['event_id'] ?? 0);

// ---- ຢືນຢັນວ່າ event ນີ້ເປັນຂອງ organizer ຄົນນີ້ແທ້ ----
$owns_event = false;
foreach ($my_events as$e) {
    if ($e['event_id'] == $selected_event) {$owns_event = true;
        break;
    }
}

// ---- ການແບ່ງໜ້າ (Pagination Setup) ----
$limit = 10; // ຈຳນວນຮ້ານຄ້າຕໍ່ 1 ໜ້າ
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) *$limit;

$stalls = [];$total_stalls = 0;
$total_pages = 1;

if ($owns_event &&$selected_event) {
    // 1. ນັບຈຳນວນຮ້ານຄ້າທັງໝົດໃນງານນີ້
    $stmt_count =$pdo->prepare("SELECT COUNT(*) FROM event_stalls WHERE event_id = ?");
    $stmt_count->execute([$selected_event]);
    $total_stalls = (int)$stmt_count->fetchColumn();

    // ຄິດໄລ່ຈຳນວນໜ້າທັງໝົດ
    $total_pages = ceil($total_stalls / $limit);
    if ($total_pages < 1)$total_pages = 1;

    // 2. ດຶງຂໍ້ມູນຮ້ານຄ້າໂດຍໃຊ້ Named Parameter (:event_id, :limit, :offset) ທັງໝົດ
    $stmt =$pdo->prepare("SELECT * FROM event_stalls WHERE event_id = :event_id ORDER BY stall_id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':event_id', $selected_event, PDO::PARAM_INT);$stmt->bindValue(':limit', $limit, PDO::PARAM_INT);$stmt->bindValue(':offset', $offset, PDO::PARAM_INT);$stmt->execute();
    $stalls =$stmt->fetchAll();
}

// ---- ດຶງຂໍ້ມູນປະເພດສິນຄ້າທັງໝົດ ສຳລັບ Dropdown ----
$stmt_cat =$pdo->query("SELECT * FROM categories ORDER BY category_name ASC");
$all_categories =$stmt_cat->fetchAll();
?>
<!doctype html>
<html lang="lo">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>ຈັດການຮ້ານຄ້າໃນງານ | LAOeventMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/organizer.css">
    <style>
        /* CSS ສຳລັບ Pagination Controls */
        .pagination {
            display: flex;
            justify-content: center;
            gap: 6px;
            margin-top: 20px;
            align-items: center;
        }
        .pagination a, .pagination span {
            padding: 6px 12px;
            border: 1px solid #CBD5E1;
            border-radius: 6px;
            text-decoration: none;
            color: #334155;
            font-size: 14px;
        }
        .pagination a:hover {
            background-color: #F1F5F9;
        }
        .pagination .active {
            background-color: #10B981;
            color: white;
            border-color: #10B981;
            font-weight: 600;
        }

        /* Modal Overlay & Card Style */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.55);
            backdrop-filter: blur(3px);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        .modal-card {
            background: #ffffff;
            padding: 24px;
            border-radius: 12px;
            width: 90%;
            max-width: 520px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            max-height: 90vh;
            overflow-y: auto;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . "/organizer_topbar.php"; ?>
    <div class="app-shell">
        <?php $active = 'stalls';
        include __DIR__ . "/organizer_sidebar.php"; ?>

        <div class="content">
            <p class="page-title">ຈັດການຮ້ານຄ້າໃນງານ</p>

            <?php if (isset($_GET['saved'])): ?><div class="alert alert-success">ບັນທຶກຮ້ານຄ້າສຳເລັດແລ້ວ</div><?php endif; ?>
            <?php if (isset($_GET['deleted'])): ?><div class="alert alert-success">ລຶບຮ້ານຄ້າສຳເລັດແລ້ວ</div><?php endif; ?>

            <?php if (empty($my_events)): ?>
                <div class="panel">
                    <div class="empty-state"><i class="ti ti-calendar-off"></i>ທ່ານຕ້ອງສ້າງງານຕະຫຼາດນັດກ່ອນ ຈຶ່ງຈະເພີ່ມຮ້ານຄ້າໄດ້. <a href="organizer_event_form.php" style="color:#10B981">ເພີ່ມງານ →</a></div>
                </div>
            <?php else: ?>

                <div class="panel">
                    <form method="GET" style="display:flex;gap:10px;align-items:end;max-width:420px">
                        <div style="flex:1">
                            <label class="field-label">ເລືອກງານຕະຫຼາດນັດ</label>
                            <select class="f-select" name="event_id" onchange="this.form.submit()" style="margin-bottom:0">
                                <?php foreach ($my_events as$e): ?>
                                    <option value="<?= $e['event_id'] ?>" <?= $e['event_id'] ==$selected_event ? 'selected' : '' ?>>
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
                                <select class="f-select" name="categories" required>
                                    <option value="">-- ເລືອກປະເພດສິນຄ້າ / Select Category --</option>
                                    <?php foreach ($all_categories as$cat): ?>
                                        <?php
                                        $lao_name = $cat['category_name'];$en_name = $cat['category_name_en'];$display = $en_name ? "{$lao_name} ({$en_name})" : $lao_name;
                                        ?>
                                        <option value="<?= htmlspecialchars($lao_name) ?>">
                                            <?= htmlspecialchars($display) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div>
                                <label class="field-label">ຊ່ອງທາງຕິດຕໍ່</label>
                                <input class="f-input" type="text" name="contact_info" placeholder="ເບີໂທ / Facebook">
                            </div>
                        </div>
                        <label class="field-label">ລາຍລະອຽດຮ້ານເພີ່ມເຕີມ</label>
                        <textarea class="f-textarea" name="description"></textarea>
                        <label class="field-label">ຮູບຮ້ານ</label>
                        <input class="f-input" type="file" name="image" accept="image/*">
                        <button type="submit" class="btn btn-green"><i class="ti ti-plus"></i> ເພີ່ມຮ້ານຄ້າ</button>
                    </form>
                </div>

                <div class="panel">
                    <h2>ຮ້ານຄ້າໃນງານນີ້ (<?= $total_stalls ?>)</h2>
                    <?php if (empty($stalls)): ?>
                        <div class="empty-state"><i class="ti ti-building-store"></i>ຍັງບໍ່ມີຮ້ານຄ້າໃນງານນີ້</div>
                    <?php else: ?>
                        <table class="data-table">
                            <tr>
                                <th></th>
                                <th>ຊື່ຮ້ານ</th>
                                <th>ເລກບູດ</th>
                                <th>ປະເພດ</th>
                                <th>ຕິດຕໍ່</th>
                                <th>ຈັດການ</th>
                            </tr>
                            <?php foreach ($stalls as$s): ?>
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
                                        <!-- ປຸ່ມແກ້ໄຂ -->
                                        <button type="button" class="btn btn-sm" style="background-color:#E0F2FE; color:#0284C7; border:none; margin-right:4px;"
                                                onclick="openEditModal(<?= htmlspecialchars(json_encode($s), ENT_QUOTES, 'UTF-8') ?>)">
                                            <i class="ti ti-edit"></i> ແກ້ໄຂ
                                        </button>

                                        <!-- ປຸ່ມລຶບ -->
                                        <a href="organizer_stall_delete.php?id=<?= $s['stall_id'] ?>&event_id=<?=$selected_event ?>"
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('ຢືນຢັນການລຶບຮ້ານຄ້ານີ້ບໍ?')"><i class="ti ti-trash"></i> ລຶບ</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>

                        <!-- ແຖບ Pagination (ແບ່ງໜ້າ) -->
                        <?php if ($total_pages > 1): ?>
                            <div class="pagination">
                                <?php if ($page > 1): ?>
                                    <a href="?event_id=<?= $selected_event ?>&page=<?= $page - 1 ?>">&laquo; ກ່ອນໜ້າ</a>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <?php if ($i ==$page): ?>
                                        <span class="active"><?= $i ?></span>
                                    <?php else: ?>
                                        <a href="?event_id=<?= $selected_event ?>&page=<?= $i ?>"><?= $i ?></a>
                                    <?php endif; ?>
                                <?php endfor; ?>

                                <?php if ($page <$total_pages): ?>
                                    <a href="?event_id=<?= $selected_event ?>&page=<?= $page + 1 ?>">ຖັດໄປ &raquo;</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Modal ສຳລັບແກ້ໄຂຮ້ານຄ້າ/ຮູບພາບ -->
    <div id="editStallModal" class="modal-overlay">
        <div class="modal-card">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px;">
                <h3 style="margin:0; font-size:18px; color:#1E293B;">ແກ້ໄຂຂໍ້ມູນຮ້ານຄ້າ</h3>
                <button type="button" onclick="closeEditModal()" style="background:none; border:none; font-size:20px; cursor:pointer; color:#64748B;">&times;</button>
            </div>

            <form action="organizer_stall_save.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="event_id" value="<?= $selected_event ?>">
                <input type="hidden" name="stall_id" id="edit_stall_id" value="0">
                
                <div style="margin-bottom:12px;">
                    <label class="field-label">ຊື່ຮ້ານ</label>
                    <input class="f-input" type="text" name="stall_name" id="edit_stall_name" required>
                </div>

                <div style="margin-bottom:12px;">
                    <label class="field-label">ເລກບູດ</label>
                    <input class="f-input" type="text" name="stall_number" id="edit_stall_number">
                </div>

                <div style="margin-bottom:12px;">
                    <label class="field-label">ປະເພດສິນຄ້າ</label>
                    <select class="f-select" name="categories" id="edit_categories" required>
                        <option value="">-- ເລືອກປະເພດສິນຄ້າ --</option>
                        <?php foreach ($all_categories as$cat): ?>
                            <?php
                            $lao_name = $cat['category_name'];$en_name = $cat['category_name_en'];$display = $en_name ? "{$lao_name} ({$en_name})" : $lao_name;
                            ?>
                            <option value="<?= htmlspecialchars($lao_name) ?>">
                                <?= htmlspecialchars($display) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div style="margin-bottom:12px;">
                    <label class="field-label">ຊ່ອງທາງຕິດຕໍ່</label>
                    <input class="f-input" type="text" name="contact_info" id="edit_contact_info">
                </div>

                <div style="margin-bottom:12px;">
                    <label class="field-label">ລາຍລະອຽດຮ້ານເພີ່ມເຕີມ</label>
                    <textarea class="f-textarea" name="description" id="edit_description"></textarea>
                </div>

                <div style="margin-bottom:12px;">
                    <label class="field-label">ຮູບປະຈຸບັນ</label>
                    <div id="edit_image_preview" style="margin-bottom:8px;"></div>
                    <label class="field-label">ອັບໂຫຼດຮູບໃໝ່ (ຖ້າຕ້ອງການປ່ຽນ)</label>
                    <input class="f-input" type="file" name="image" accept="image/*">
                </div>

                <div style="display:flex; justify-content:flex-end; gap:8px; margin-top:20px;">
                    <button type="button" class="btn" style="background:#E2E8F0; color:#475569;" onclick="closeEditModal()">ຍົກເລີກ</button>
                    <button type="submit" class="btn btn-green"><i class="ti ti-device-floppy"></i> ບັນທຶກການແກ້ໄຂ</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(stall) {
            document.getElementById('edit_stall_id').value = stall.stall_id;
            document.getElementById('edit_stall_name').value = stall.stall_name || '';
            document.getElementById('edit_stall_number').value = stall.stall_number || '';
            document.getElementById('edit_categories').value = stall.categories || '';
            document.getElementById('edit_contact_info').value = stall.contact_info || '';
            document.getElementById('edit_description').value = stall.description || '';

            // ສະແດງຮູບເກົ່າ
            const previewContainer = document.getElementById('edit_image_preview');
            if (stall.image) {
                let imgUrl = stall.image.match(/^https?:\/\//i) ? stall.image : 'uploads/' + stall.image.replace(/^\//, '');
                previewContainer.innerHTML = `<img src="${imgUrl}" style="width:80px; height:80px; object-fit:cover; border-radius:8px; border:1px solid #CBD5E1;">`;
            } else {
                previewContainer.innerHTML = `<span style="font-size:12px; color:#94A3B8;">ບໍ່ມີຮູບພາບ</span>`;
            }

            document.getElementById('editStallModal').style.display = 'flex';
        }

        function closeEditModal() {
            document.getElementById('editStallModal').style.display = 'none';
        }

        // ປິດ Modal ເມື່ອກົດພື້ນຫຼັງ
        window.onclick = function(event) {
            const modal = document.getElementById('editStallModal');
            if (event.target === modal) {
                closeEditModal();
            }
        }
    </script>
    <script src="assets/site-i18n.js"></script>
</body>

</html>