<?php
// $active ຕ້ອງຖືກກຳນົດຢູ່ໜ້າແມ່ ກ່ອນ include ໄຟລ໌ນີ້
// ຄ່າທີ່ໃຊ້ໄດ້: dashboard, events, add_event, stalls, reports, profile
$active = $active ?? '';
?>
<div class="sidebar">
    <div class="org-name">
        <?= htmlspecialchars(!empty($company_name) ? $company_name : $organizer_name) ?>
        <small><?= htmlspecialchars($organizer_name) ?></small>
    </div>

    <a href="organizer_dashboard.php" data-i18n="organizerDashboard" class="side-item <?= $active === 'dashboard' ? 'active' : '' ?>">
        <i class="ti ti-dashboard"></i> ແຜງຄວບຄຸມ
    </a>
    <a href="organizer_events.php" data-i18n="organizerEvents" class="side-item <?= $active === 'events' ? 'active' : '' ?>">
        <i class="ti ti-calendar-event"></i> ຈັດການງານຕະຫຼາດນັດ
    </a>
    <a href="organizer_event_form.php" data-i18n="organizerAddEvent" class="side-item <?= $active === 'add_event' ? 'active' : '' ?>">
        <i class="ti ti-plus"></i> ເພີ່ມງານໃໝ່
    </a>
    <a href="organizer_stalls.php" data-i18n="organizerStalls" class="side-item <?= $active === 'stalls' ? 'active' : '' ?>">
        <i class="ti ti-building-store"></i> ຈັດການຮ້ານຄ້າ
    </a>
    <a href="organizer_reports.php" data-i18n="organizerReports" class="side-item <?= $active === 'reports' ? 'active' : '' ?>">
        <i class="ti ti-chart-bar"></i> ລາຍງານ ແລະ ຣີວິວ
    </a>
    <a href="organizer_profile.php" data-i18n="organizerProfile" class="side-item <?= $active === 'profile' ? 'active' : '' ?>">
        <i class="ti ti-user-cog"></i> ຕັ້ງຄ່າໂປຣຟາຍ
    </a>
</div>
