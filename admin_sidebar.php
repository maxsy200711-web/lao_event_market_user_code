<?php $active = $active ?? ''; ?>
<div class="sidebar">
    <div class="admin-name">
        <span style="display:block;font-size:16px;font-weight:700;letter-spacing:.02em">ADMINISTRATOR</span>
        <small>ADMIN</small>
    </div>
    <a href="admin_dashboard.php" class="side-item <?= $active === 'overview' ? 'active' : '' ?>"><i class="ti ti-chart-bar"></i> ໜ້າພາບລວມ</a>
    <a href="admin_approvals.php" class="side-item <?= $active === 'approvals' ? 'active' : '' ?>"><i class="ti ti-check"></i> ອະນຸມັດກິດຈະກຳ</a>
    <a href="admin_events.php" class="side-item <?= $active === 'events' ? 'active' : '' ?>"><i class="ti ti-calendar-event"></i> ຈັດການກິດຈະກຳ</a>
    <a href="admin_catalog.php" class="side-item <?= $active === 'catalog' ? 'active' : '' ?>"><i class="ti ti-category"></i> ໝວດໝູ່ ແລະ ແຂວງ</a>
    <a href="admin_stalls.php" class="side-item <?= $active === 'stalls' ? 'active' : '' ?>"><i class="ti ti-building-store"></i> ຈັດການຮ້ານຄ້າ</a>
    <a href="admin_users.php" class="side-item <?= $active === 'users' ? 'active' : '' ?>"><i class="ti ti-users"></i> ຈັດການຜູ້ໃຊ້</a>
    <a href="admin_organizers.php" class="side-item <?= $active === 'organizers' ? 'active' : '' ?>"><i class="ti ti-building-store"></i> ຜູ້ຈັດງານ</a>
    <a href="admin_organizer_requests.php" class="side-item <?= $active === 'organizer_requests' ? 'active' : '' ?>"><i class="ti ti-user-check"></i> ຄຳຮ້ອງຜູ້ຈັດງານ</a>
    <a href="admin_reviews.php" class="side-item <?= $active === 'reviews' ? 'active' : '' ?>"><i class="ti ti-message-2"></i> ຈັດການຣີວິວ</a>
    <a href="admin_reports.php" class="side-item <?= $active === 'reports' ? 'active' : '' ?>"><i class="ti ti-file-analytics"></i> ລາຍງານສະຖິຕິ</a>
</div>
