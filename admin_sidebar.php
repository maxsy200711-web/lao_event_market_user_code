<?php
// $active ຕ້ອງຖືກກຳນົດຢູ່ໜ້າແມ່ ກ່ອນ include ໄຟລ໌ນີ້
// ຄ່າທີ່ໃຊ້ໄດ້: overview, approvals, users, reviews, reports
$active = $active ?? '';
?>
<div class="sidebar">
    <div class="admin-name">
        <?= htmlspecialchars($admin_name) ?>
        <small><?= htmlspecialchars($admin_role) ?></small>
    </div>

    <a href="admin_dashboard.php" class="side-item <?= $active === 'overview' ? 'active' : '' ?>">
        <i class="ti ti-chart-bar"></i> ພາບລວມລະບົບ
    </a>
    <a href="admin_approvals.php" class="side-item <?= $active === 'approvals' ? 'active' : '' ?>">
        <i class="ti ti-check"></i> ອະນຸມັດງານ
    </a>
    <a href="admin_users.php" class="side-item <?= $active === 'users' ? 'active' : '' ?>">
        <i class="ti ti-users"></i> ຈັດການຜູ້ໃຊ້
    </a>
    <a href="admin_reviews.php" class="side-item <?= $active === 'reviews' ? 'active' : '' ?>">
        <i class="ti ti-message-2"></i> ຈັດການລິວິວ
    </a>
    <a href="admin_reports.php" class="side-item <?= $active === 'reports' ? 'active' : '' ?>">
        <i class="ti ti-file-analytics"></i> ລາຍງານສະຖິຕິ
    </a>
</div>
