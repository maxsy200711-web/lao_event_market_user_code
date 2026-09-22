<?php
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_admin.php";

$event_id = (int) ($_GET['id'] ?? 0);
$is_ajax = isset($_GET['ajax']) && $_GET['ajax'] == '1';

if (!$event_id) {
    echo "ບໍ່ພົບ ID ຂອງງານ";
    exit;
}

// ດຶງຂໍ້ມູນລາຍລະອຽດຂອງງານ
$sql = "SELECT 
            e.*, 
            p.province_name, 
            c.category_name, 
            o.organizer_name,
            o.company_name
        FROM events e
        LEFT JOIN provinces p ON p.province_id = e.province_id
        LEFT JOIN categories c ON c.category_id = e.category_id
        LEFT JOIN organizers o ON o.organizer_id = e.organizer_id
        WHERE e.event_id = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$event_id]);
$event = $stmt->fetch();

if (!$event) {
    echo "ບໍ່ພົບຂໍ້ມູນງານ";
    exit;
}

function imageUrl(?string $path): string {
    if (!$path) return '';
    if (preg_match('/^https?:\/\//i', $path)) return $path;
    return 'uploads/' . ltrim($path, '/');
}

$badge = ['pending' => 'status-pending', 'approved' => 'status-approved', 'rejected' => 'status-rejected'];
$label = ['pending' => 'ລໍຖ້າອະນຸມັດ', 'approved' => 'ອະນຸມັດແລ້ວ', 'rejected' => 'ປະຕິເສດແລ້ວ'];
?>

<style>
    .modal-banner { width: 100%; max-height: 300px; object-fit: cover; border-radius: 8px; margin-bottom: 16px; }
    .modal-title { font-size: 20px; font-weight: 700; color: #111827; margin-bottom: 12px; }
    .modal-meta-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; background: #F1F5F9; padding: 14px; border-radius: 8px; margin-bottom: 16px; }
    .modal-meta-item { display: flex; align-items: center; gap: 8px; font-size: 13px; }
    .modal-desc { line-height: 1.6; font-size: 14px; color: #475569; white-space: pre-line; }
    .badge { padding: 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .status-pending { background: #FEF3C7; color: #D97706; }
    .status-approved { background: #D1FAE5; color: #059669; }
    .status-rejected { background: #FEE2E2; color: #DC2626; }
</style>

<div>
    <?php if (!empty($event['banner_img'])): ?>
        <img src="<?= htmlspecialchars(imageUrl($event['banner_img'])) ?>" class="modal-banner" alt="Event Banner">
    <?php endif; ?>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
        <h2 class="modal-title" style="margin:0;"><?= htmlspecialchars($event['title']) ?></h2>
        <span class="badge <?= $badge[$event['status']] ?? 'status-pending' ?>">
            <?= htmlspecialchars($label[$event['status']] ?? $event['status']) ?>
        </span>
    </div>

    <div class="modal-meta-grid">
        <div class="modal-meta-item">
            <i class="ti ti-user"></i> <b>ຜູ້ຈັດງານ:</b> 
            <?= htmlspecialchars($event['company_name'] ?: $event['organizer_name'] ?: 'ບໍ່ມີຂໍ້ມູນ') ?>
        </div>
        <div class="modal-meta-item">
            <i class="ti ti-category"></i> <b>ປະເພດງານ:</b> 
            <?= htmlspecialchars($event['category_name'] ?: '-') ?>
        </div>
        <div class="modal-meta-item">
            <i class="ti ti-calendar"></i> <b>ວັນທີ:</b> 
            <?= htmlspecialchars($event['start_date']) ?> <?= !empty($event['end_date']) ? 'ເຖິງ ' . htmlspecialchars($event['end_date']) : '' ?>
        </div>
        <div class="modal-meta-item">
            <i class="ti ti-clock"></i> <b>ເວລາ:</b> 
            <?= htmlspecialchars($event['time'] ?: '-') ?>
        </div>
        <div class="modal-meta-item">
            <i class="ti ti-map-pin"></i> <b>ສະຖານທີ່:</b> 
            <?= htmlspecialchars($event['location']) ?> (<?= htmlspecialchars($event['province_name'] ?: '-') ?>)
        </div>
        <?php if (!empty($event['map_url'])): ?>
        <div class="modal-meta-item">
            <i class="ti ti-map-2"></i> <b>ແຜນທີ່:</b> 
            <a href="<?= htmlspecialchars($event['map_url']) ?>" target="_blank" style="color: #10B981;">ເບິ່ງແຜນທີ່ Google Maps</a>
        </div>
        <?php endif; ?>
    </div>

    <h4 style="margin-bottom: 8px;">ລາຍລະອຽດງານ:</h4>
    <div class="modal-desc"><?= nl2br(htmlspecialchars($event['description'] ?: 'ບໍ່ມີຂໍ້ມູນລາຍລະອຽດ')) ?></div>
</div>