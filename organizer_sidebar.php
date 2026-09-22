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

    <a href="organizer_dashboard.php" class="side-item <?= $active === 'dashboard' ? 'active' : '' ?>">
        <i class="ti ti-dashboard"></i> ແຜງຄວບຄຸມ
    </a>
    <a href="organizer_events.php" class="side-item <?= $active === 'events' ? 'active' : '' ?>">
        <i class="ti ti-calendar-event"></i> ຈັດການງານຕະຫຼາດນັດ
    </a>
    <a href="organizer_event_form.php" class="side-item <?= $active === 'add_event' ? 'active' : '' ?>">
        <i class="ti ti-plus"></i> ເພີ່ມງານໃໝ່
    </a>
    <a href="organizer_stalls.php" class="side-item <?= $active === 'stalls' ? 'active' : '' ?>">
        <i class="ti ti-building-store"></i> ຈັດການຮ້ານຄ້າ
    </a>
    <a href="organizer_reports.php" class="side-item <?= $active === 'reports' ? 'active' : '' ?>">
        <i class="ti ti-chart-bar"></i> ລາຍງານ ແລະ ລິວິວ
    </a>
    <a href="organizer_profile.php" class="side-item <?= $active === 'profile' ? 'active' : '' ?>">
        <i class="ti ti-user-cog"></i> ຕັ້ງຄ່າໂປຣຟາຍ
    </a>
</div>