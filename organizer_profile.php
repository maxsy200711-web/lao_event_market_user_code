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
                if (!empty($_SESSION['user_id'])) {
                    $user_stmt = $pdo->prepare("UPDATE users SET password = ? WHERE user_id = ?");
                    $user_stmt->execute([$hashed_pwd, (int)$_SESSION['user_id']]);
                }
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
$stmt = $pdo->prepare("SELECT o.*, u.email FROM organizers o LEFT JOIN organizer_users ou ON ou.organizer_id = o.organizer_id LEFT JOIN users u ON u.user_id = ou.user_id WHERE o.organizer_id = ? LIMIT 1");
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
    <style>
        :root {
            --primary: #10B981;
            --primary-hover: #059669;
            --dark-sidebar: #0F172A;
            --bg: #F8FAFC;
            --border: #E2E8F0;
            --text-muted: #64748B;
            --font-lao: 'Noto Sans Lao', sans-serif;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--font-lao); }
        body { background-color: var(--bg); display: flex; flex-direction: column; min-height: 100vh; }

        header.top {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .brand { font-size: 18px; font-weight: 700; color: #0F172A; text-decoration: none; }
        .brand b { color: var(--primary); }
        .brand small { font-size: 12px; color: var(--text-muted); margin-left: 6px; font-weight: 400; }

        .top-user { display: flex; align-items: center; gap: 12px; font-size: 14px; color: #334155; }
        .btn-logout { text-decoration: none; color: #EF4444; background: #FEF2F2; padding: 6px 12px; border-radius: 6px; font-size: 13px; font-weight: 500; }

        .layout-body { display: flex; flex: 1; }

        /* Sidebar Styling */
        .sidebar {
            width: 240px;
            background: var(--dark-sidebar);
            color: #94A3B8;
            padding: 20px 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }
        .org-name {
            padding: 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 8px;
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 16px;
            word-break: break-all;
        }
        .org-name small { display: block; color: #94A3B8; font-weight: 400; font-size: 12px; margin-top: 2px; }
        .side-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            color: #94A3B8;
            text-decoration: none;
            border-radius: 8px;
            font-size: 14px;
            transition: 0.2s;
        }
        .side-item:hover { background: rgba(255, 255, 255, 0.08); color: #ffffff; }
        .side-item.active { background: var(--primary); color: #ffffff; font-weight: 600; }

        .main-content { flex: 1; padding: 30px; }
        .card { background: #ffffff; border: 1px solid var(--border); border-radius: 12px; padding: 24px; max-width: 700px; }
        .card-title { font-size: 18px; font-weight: 700; color: #0F172A; margin-bottom: 20px; }

        .alert { padding: 12px 16px; border-radius: 8px; font-size: 14px; margin-bottom: 20px; }
        .alert-success { background: #D1FAE5; color: #065F46; }
        .alert-error { background: #FEE2E2; color: #991B1B; }

        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group.full { grid-column: span 2; }
        .form-group label { display: block; font-size: 13px; font-weight: 600; color: #1E293B; margin-bottom: 6px; }
        .form-control { width: 100%; height: 42px; border: 1px solid var(--border); border-radius: 8px; padding: 0 12px; font-size: 14px; outline: none; }
        .form-control:focus { border-color: var(--primary); }
        .form-control[readonly] { background-color: #F1F5F9; color: var(--text-muted); cursor: not-allowed; }

        .btn-submit { background: var(--primary); color: #ffffff; border: none; padding: 10px 20px; border-radius: 8px; font-size: 14px; font-weight: 600; cursor: pointer; }
        .btn-submit:hover { background: var(--primary-hover); }
    </style>
</head>
<body>

<header class="top">
    <a href="organizer_dashboard.php" class="brand"><b>LAO</b>event<b>Market</b> <small>• ຜູ້ຈັດງານ</small></a>
    <div class="top-user">
        <span><i class="ti ti-user-circle"></i> <?= htmlspecialchars($organizer_name) ?></span>
        <a href="organizer_logout.php" class="btn-logout"><i class="ti ti-logout"></i> ອອກຈາກລະບົບ</a>
    </div>
</header>

<div class="layout-body">
    <!-- Include Sidebar -->
    <?php require_once __DIR__ . "/organizer_sidebar.php"; ?>

    <main class="main-content">
        <div class="card">
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

</body>
</html>
