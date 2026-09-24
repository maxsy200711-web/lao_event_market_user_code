<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ກວດສອບ Session ການ Login (ຮອງຮັບທັງ User ແລະ Organizer)
$user_email = $_SESSION['user_email'] ?? $_SESSION['organizer_email'] ?? null;
$user_role  = $_SESSION['role'] ?? null;

// ສາມາດຕັດເອົາຊື່ prefix ກ່ອນໜ້າ @ ມາສະແດງເປັນ Display Name ໄດ້
$display_name = '';
if ($user_email) {
    $parts = explode('@', $user_email);
    $display_name = $parts[0]; // ເອົາຊື່ໜ້າ @gmail.com
}
?>

<style>
.user-menu {
    display: flex;
    align-items: center;
    gap: 10px;
    background: #F1F5F9;
    padding: 6px 14px;
    border-radius: 20px;
    border: 1px solid #E2E8F0;
}
.user-avatar {
    width: 28px;
    height: 28px;
    background: #10B981;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: bold;
}
.user-email-text {
    font-size: 13px;
    font-weight: 600;
    color: #1E293B;
    max-width: 150px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
.btn-logout {
    color: #EF4444;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    margin-left: 4px;
}
.btn-logout:hover {
    color: #DC2626;
}
.btn-profile {
    color: #059669;
    text-decoration: none;
    font-size: 16px;
    display: flex;
    align-items: center;
    margin-left: 4px;
}
</style>

<?php if ($user_email): ?>
    <!-- ສະແດງເມື່ອ Login ເຂົ້າສູ່ລະບົບແລ້ວ -->
    <div class="user-menu">
        <div class="user-avatar">
            <i class="ti ti-user"></i>
        </div>
        <span class="user-email-text" title="<?= htmlspecialchars($user_email) ?>">
            <?= htmlspecialchars($display_name) ?>
        </span>
        <?php if ($user_role === 'user'): ?>
            <a href="user_profile.php" class="btn-profile" title="Profile">
                <i class="ti ti-user-cog"></i>
            </a>
        <?php endif; ?>
        <a href="logout.php" class="btn-logout" title="Logout">
            <i class="ti ti-logout"></i>
        </a>
    </div>
<?php else: ?>
    <!-- ສະແດງເມື່ອຍັງບໍ່ທັນ Login (ໂຮມເປັນປຸ່ມດຽວ) -->
    <a href="login.php" class="btn btn-green" data-i18n="loginRegister">
        ເຂົ້າສູ່ລະບົບ / ສະໝັກສະມາຊິກ
    </a>
<?php endif; ?>
