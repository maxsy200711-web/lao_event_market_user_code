<?php
session_start();
require_once __DIR__ . "/config.php";

// ກວດສອບ Session ວ່າໄດ້ ເຂົ້າສູ່ລະບົບ ແລ້ວຫຼືຍັງ
if (empty($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$success_msg = '';
$error_msg = '';

// 1. ຈັດການການ Update ຂໍ້ມູນຕອນສົ່ງ Form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name    = trim($_POST['full_name'] ?? '');
    $email        = trim($_POST['email'] ?? '');
    $phone        = trim($_POST['phone'] ?? '');
    $new_password = $_POST['new_password'] ?? '';

    if ($full_name === '' || $email === '') {
        $error_msg = 'ກະລຸນາປ້ອນ ຊື່-ນາມສະກຸນ ແລະ ອີເມວ';
    } else {
        // ກວດສອບວ່າ email ຊໍ້າກັບ user ອື່ນບໍ
        $chk_stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ? AND user_id != ?");
        $chk_stmt->execute([$email, $user_id]);
        if ($chk_stmt->fetch()) {
            $error_msg = 'ອີເມວນີ້ຖືກໃຊ້ງານແລ້ວ ໂດຍຜູ້ໃຊ້ອື່ນ';
        } else {
            // ຖ້າມີການປ່ຽນລະຫັດຜ່ານໃໝ່
            if (!empty($new_password)) {
                if (strlen($new_password) < 6) {
                    $error_msg = 'ລະຫັດຜ່ານໃໝ່ຕ້ອງມີຢ່າງໜ້ອຍ 6 ຕົວອັກສອນ';
                } else {
                    $hashed_pwd = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, password = ? WHERE user_id = ?");
                    $update_stmt->execute([$full_name, $email, $phone, $hashed_pwd, $user_id]);
                    $success_msg = 'ບັນທຶກຂໍ້ມູນ ແລະ ປ່ຽນລະຫັດຜ່ານສຳເລັດແລ້ວ';
                }
            } else {
                // ອັບເດດຂໍ້ມູນທົ່ວໄປ (ບໍ່ປ່ຽນລະຫັດຜ່ານ)
                $update_stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ? WHERE user_id = ?");
                $update_stmt->execute([$full_name, $email, $phone, $user_id]);
                $success_msg = 'ບັນທຶກຂໍ້ມູນສຳເລັດແລ້ວ';
            }

            // ອັບເດດ session ໃຫ້ເປັນຊື່ໃໝ່
            if (empty($error_msg)) {
                $_SESSION['full_name'] = $full_name;
            }
        }
    }
}

// 2. ດຶງຂໍ້ມູນ User ປັດຈຸບັນມາສະແດງ
$stmt = $pdo->prepare("SELECT user_id, full_name, email, phone, create_at FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    die("ບໍ່ພົບຂໍ້ມູນຜູ້ໃຊ້ງານ");
}
?>
<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ໂປຣຟາຍຂອງຂ້ອຍ - LAOeventMarket</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
    <style>
        :root {
            --primary: #10B981;
            --primary-hover: #059669;
            --dark: #111827;
            --bg: #F8FAFC;
            --border: #E2E8F0;
            --text-muted: #64748B;
            --font-lao: 'Noto Sans Lao', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: var(--font-lao); }
        body { background-color: var(--bg); color: #334155; min-height: 100vh; }

        header.top {
            background: #ffffff;
            border-bottom: 1px solid var(--border);
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            font-size: 20px;
            color: var(--dark);
            font-weight: 700;
        }
        .brand b { color: var(--primary); }
        .brand-mark { background: #ECFDF5; color: var(--primary); padding: 6px; border-radius: 8px; display: flex; }

        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .card {
            background: #ffffff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 16px;
            border-bottom: 1px solid var(--border);
        }

        .card-header i {
            font-size: 28px;
            color: var(--primary);
        }

        .card-header h2 {
            font-size: 20px;
            color: var(--dark);
        }

        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .alert-success { background: #D1FAE5; color: #065F46; border: 1px solid #A7F3D0; }
        .alert-error { background: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 0 14px;
            font-size: 14px;
            outline: none;
            background: #ffffff;
        }

        .form-control:focus {
            border-color: var(--primary);
        }

        .form-control[readonly] {
            background-color: #F1F5F9;
            color: var(--text-muted);
            cursor: not-allowed;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--primary-hover); }
        .btn-outline { border: 1px solid var(--border); color: var(--dark); background: white; }

        .form-actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<header class="top">
    <a class="brand" href="index.php">
        <span class="brand-mark"><i class="ti ti-home-2"></i></span>
        <span><b>LAO</b>event<b>Market</b></span>
    </a>
    <a href="index.php" class="btn btn-outline"><i class="ti ti-arrow-left"></i> ກັບໜ້າຫຼັກ</a>
</header>

<div class="container">
    <div class="card">
        <div class="card-header">
            <i class="ti ti-user-circle"></i>
            <h2>ຈັດການຂໍ້ມູນໂປຣຟາຍ</h2>
        </div>

        <?php if ($success_msg): ?>
            <div class="alert alert-success"><i class="ti ti-circle-check"></i> <?= htmlspecialchars($success_msg) ?></div>
        <?php endif; ?>

        <?php if ($error_msg): ?>
            <div class="alert alert-error"><i class="ti ti-alert-circle"></i> <?= htmlspecialchars($error_msg) ?></div>
        <?php endif; ?>

        <form method="POST" action="user_profile.php">
            <!-- ຂໍ້ມູນທີ່ Readonly (ແກ້ໄຂບໍ່ໄດ້) -->
            <div class="form-row">
                <div class="form-group">
                    <label>ໄອດີຜູ້ໃຊ້ (User ID)</label>
                    <input type="text" class="form-control" value="#<?= (int)$user['user_id'] ?>" readonly>
                </div>
                <div class="form-group">
                    <label>ວັນທີສ້າງບັນຊີ (Created At)</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($user['create_at'] ?? 'N/A') ?>" readonly>
                </div>
            </div>

            <!-- ຂໍ້ມູນທີ່ສາມາດແກ້ໄຂໄດ້ -->
            <div class="form-group">
                <label>ຊື່ ແລະ ນາມສະກຸນ</label>
                <input type="text" name="full_name" class="form-control" value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>

            <div class="form-group">
                <label>ອີເມວ (Email)</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>

            <div class="form-group">
                <label>ເບີໂທລະສັບ (Phone)</label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone'] ?? '') ?>" placeholder="020XXXXXXXX">
            </div>

            <hr style="border: 0; border-top: 1px solid var(--border); margin: 20px 0;">

            <div class="form-group">
                <label>ປ່ຽນລະຫັດຜ່ານໃໝ່ (ປ່ອຍຫວ່າງໄວ້ ຖ້າບໍ່ຕ້ອງການປ່ຽນ)</label>
                <input type="password" name="new_password" class="form-control" placeholder="••••••••">
            </div>

            <div class="form-actions">
                <a href="index.php" class="btn btn-outline">ຍົກເລີກ</a>
                <button type="submit" class="btn btn-primary"><i class="ti ti-device-floppy"></i> ບັນທຶກການປ່ຽນແປງ</button>
            </div>
        </form>

        <p style="margin-top:20px;text-align:center">
            <a href="become_organizer.php" style="color:#10B981;font-weight:600;text-decoration:none">
                Become an Organizer
            </a>
        </p>
    </div>
</div>

</body>
</html>
