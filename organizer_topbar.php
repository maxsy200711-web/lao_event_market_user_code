<div class="topbar">
    <div class="brand" style="display:flex;align-items:center;gap:8px;white-space:nowrap">
        <?php include __DIR__ . '/site_logo.php'; ?>
        <span data-i18n="organizerRole" style="font-weight:400;color:#94A3B8;font-size:12px">· ຜູ້ຈັດງານ</span>
    </div>
    <div class="top-actions">
        <span><i class="ti ti-user-circle"></i> <?= htmlspecialchars($organizer_name) ?></span>
        <a href="organizer_logout.php" data-i18n="logout" class="btn-logout"><i class="ti ti-logout"></i> ອອກຈາກລະບົບ</a>
    </div>
</div>
