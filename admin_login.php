<?php
session_start();
$error = $_GET['error'] ?? '';
?>
<!doctype html>
<html lang="lo">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Admin Login | LAOeventMarket</title>
<link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<link rel="stylesheet" href="assets/admin.css">
</head>
<body>
<div class="admin-login-wrap">
<div class="admin-login-card">
    <div style="text-align:center;margin-bottom:16px">
        <div style="font-size:18px;font-weight:700">
            <span style="color:#111827">LAO</span><span style="color:#10B981">event</span><span style="color:#111827">Market</span>
        </div>
        <span class="admin-badge">ADMIN PANEL</span>
    </div>

    <?php if ($error === '1'): ?>
        <p style="background:#FEE2E2;color:#B91C1C;font-size:12px;padding:8px 10px;border-radius:8px;margin-bottom:14px;text-align:center">
            ອີເມວ ຫຼື ລະຫັດຜ່ານ ບໍ່ຖືກຕ້ອງ
        </p>
    <?php endif; ?>

    <form action="admin_login_process.php" method="POST">
        <label>ອີເມວ Admin</label>
        <input type="email" name="email" placeholder="admin@laoeventmarket.com" required>

        <label>ລະຫັດຜ່ານ</label>
        <input type="password" name="password" placeholder="••••••••" required>

        <label>ລະຫັດຢືນຢັນ 2FA <span style="color:#94A3B8;font-weight:400">(ຖ້າມີ)</span></label>
        <input type="text" name="twofa" placeholder="000000" maxlength="6">

        <button type="submit" class="btn-access">Access Dashboard</button>
    </form>

    <p class="notice">Restricted Area — Authorized Personnel Only</p>
</div>
</div>
</body>
</html>
