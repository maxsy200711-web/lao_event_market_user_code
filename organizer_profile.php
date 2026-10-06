<?php
session_start();
require_once __DIR__ . "/config.php";
require_once __DIR__ . "/auth_organizer.php";

$organizer_id = (int)$_SESSION['organizer_id'];
$success_msg  = '';
$error_msg    = '';

// 1. ຈັດການການ Update ຂໍ້ມູນ
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $organizer_name = trim($_POST['organizer_name'] ?? '');
    $company_name   = trim($_POST['company_name'] ?? '');
    $phone          = trim($_POST['phone'] ?? '');
    $new_password   = $_POST['new_password'] ?? '';

    if ($organizer_name === '') {
        $error_msg = 'ກະລຸນາປ້ອນຊື່ຜູ້ຈັດງານ';
    } else {
        if (!empty($new_password)) {
            if (strlen($new_password) < 6) {
                $error_msg = 'ລະຫັດຜ່ານໃໝ່ຕ້ອງມີຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ';
            } else {
                $hashed_pwd = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE organizers SET organizer_name = ?, company_name = ?, phone = ? WHERE organizer_id = ?");
                $stmt->execute([$organizer_name, $company_name, $phone, $organizer_id]);
                $account_stmt = $pdo->prepare('UPDATE organizers SET password = ? WHERE organizer_id = ?');
                $account_stmt->execute([$hashed_pwd, $organizer_id]);
                $success_msg = 'ບັນທຶກຂໍ້ມູນ ແລະ ປ່ຽນລະຫັດຜ່ານສຳເລັດ';
            }
        } else {
            $stmt = $pdo->prepare("UPDATE organizers SET organizer_name = ?, company_name = ?, phone = ? WHERE organizer_id = ?");
            $stmt->execute([$organizer_name, $company_name, $phone, $organizer_id]);
            $success_msg = 'ບັນທຶກຂໍ້ມູນສຳເລັດ';
        }

        // ອັບເດດຄ່າໃນ Session ໃຫ້ສະແດງຜົນທັນທີ
        if (empty($error_msg)) {
            $_SESSION['organizer_name'] = $organizer_name;
            $_SESSION['company_name']   = $company_name;
        }
    }
}

// 2. ດຶງຂໍ້ມູນ Organizer ປັດຈຸບັນ
$stmt = $pdo->prepare('SELECT o.* FROM organizers o WHERE o.organizer_id = ? LIMIT 1');
$stmt->execute([$organizer_id]);
$org = $stmt->fetch();

if (!$org) {
    die("ບໍ່ພົບຂໍ້ມູນຜູ້ຈັດງານ");
}

$organizer_name = $org['organizer_name'];
$company_name   = $org['company_name'] ?? '';
$active         = 'profile';
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ໂປຣຟາຍຜູ້ຈັດງານ - LAOeventMarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <link rel="stylesheet" href="assets/organizer.css">
    <style>
        .topbar > .brand { display: flex; align-items: center; gap: 8px; white-space: nowrap; }
        .topbar > .brand small { font-size: 12px; color: #64748B; font-weight: 400; }
        .profile-panel { max-width: 700px; }
        .profile-panel .card-title { font-size: 18px; font-weight: 700; margin-bottom: 20px; }
        .profile-panel .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .profile-panel .form-group { margin-bottom: 16px; }
        .profile-panel .form-group.full { grid-column: span 2; }
        .profile-panel .form-group label { display: block; font-size: 13px; font-weight: 600; margin-bottom: 6px; }
        .profile-panel .form-control { width: 100%; height: 42px; border: 1px solid #E2E8F0; border-radius: 8px; padding: 0 12px; font-size: 14px; outline: none; }
        .profile-panel .form-control:focus { border-color: #10B981; }
        .profile-panel .form-control[readonly] { background: #F1F5F9; color: #64748B; cursor: not-allowed; }
        .profile-panel .btn-submit { background: #10B981; color: #fff; border: 0; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .profile-panel .btn-submit:hover { background: #059669; }
        @media (max-width: 600px) { .profile-panel .form-grid { grid-template-columns: 1fr; } .profile-panel .form-group.full { grid-column: auto; } }
    </style>
</head>
<body>

<div class="topbar">
    <a href="organizer_dashboard.php" class="brand"><?php include __DIR__ . '/site_logo.php'; ?> <small>• ຜູ້ຈັດງານ</small></a>
    <div class="top-actions">
        <span><i class="ti ti-user-circle"></i> <?= htmlspecialchars($organizer_name) ?></span>
        <a href="organizer_logout.php" class="btn-logout"><i class="ti ti-logout"></i> ອອກຈາກລະບົບ</a>
    </div>
</div>

<div class="app-shell">
    <!-- Include Sidebar -->
    <?php require_once __DIR__ . "/organizer_sidebar.php"; ?>

    <main class="content">
        <div class="panel profile-panel">
            <h2 class="card-title"><i class="ti ti-building-cog"></i> ຈັດການຂໍ້ມູນອົງກອນ / ຜູ້ຈັດງານ</h2>

            <?php if ($success_msg): ?>
                <div class="alert alert-success"><i class="ti ti-circle-check"></i> <?= htmlspecialchars($success_msg) ?></div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="alert alert-error"><i class="ti ti-alert-circle"></i> <?= htmlspecialchars($error_msg) ?></div>
            <?php endif; ?>

            <form method="POST" action="organizer_profile.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label>ໄອດີ (Organizer ID)</label>
                        <input type="text" class="form-control" value="#<?= (int)$org['organizer_id'] ?>" readonly>
                    </div>
                    <div class="form-group">
                        <label>ວັນທີລົງທະບຽນ</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($org['created_at'] ?? 'N/A') ?>" readonly>
                    </div>

                    <div class="form-group full">
                        <label>ຊື່ບໍລິສັດ / ອົງກອນ (Company / Organization Name)</label>
                        <input type="text" name="company_name" class="form-control" value="<?= htmlspecialchars($org['company_name'] ?? '') ?>" placeholder="ຕົວຢ່າງ: ບໍລິສັດ ອີເວັ້ນ ລາວ ຈຳກັດ">
                    </div>

                    <div class="form-group">
                        <label>ຊື່ຜູ້ຕິດຕໍ່ / ຜູ້ຈັດງານ (Organizer Name)</label>
                        <input type="text" name="organizer_name" class="form-control" value="<?= htmlspecialchars($org['organizer_name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label>ເບີໂທລະສັບ (Phone Number)</label>
                        <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($org['phone'] ?? '') ?>" placeholder="020XXXXXXXX">
                    </div>

                    <div class="form-group full">
                        <label>ອີເມວ (Email)</label>
                        <input type="email" class="form-control" value="<?= htmlspecialchars($org['email']) ?>" readonly>
                    </div>

                    <div class="form-group full">
                        <label>ປ່ຽນລະຫັດຜ່ານໃໝ່ (ປ່ອຍຫວ່າງໄວ້ ຖ້າບໍ່ຕ້ອງການປ່ຽນ)</label>
                        <input type="password" name="new_password" class="form-control" placeholder="••••••••">
                    </div>
                </div>

                <div style="margin-top: 10px;">
                    <button type="submit" class="btn-submit"><i class="ti ti-device-floppy"></i> ບັນທຶກການປ່ຽນແປງ</button>
                </div>
            </form>
        </div>
    </main>
</div>

<script src="assets/site-i18n.js"></script>
    </body>
</html>
