<?php
session_start();
date_default_timezone_set('Asia/Vientiane'); // ຕັ້ງເຂດເວລາໃຫ້ເປັນເວລາລາວ (+7)
require_once __DIR__ . "/config.php";

$email = $_SESSION['auth_email'] ?? '';
$role  = $_SESSION['auth_role'] ?? 'user';
$demo_otp = $_SESSION['demo_otp'] ?? '';

if (!$email) {
    header("Location: login.php");
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input_otp = trim($_POST['otp'] ?? '');
    $table = ($role === 'organizer') ? 'organizers' : 'users';
    
    $current_time = date("Y-m-d H:i:s"); // ເອົາເວລາປັດຈຸບັນຂອງ PHP

    // ເອົາ $current_time ມາທຽບກັບ otp_expires_at ແທນການໃຊ້ NOW()
    $stmt = $pdo->prepare("SELECT * FROM {$table} WHERE email = ? AND otp_code = ? AND otp_expires_at > ?");
    $stmt->execute([$email, $input_otp, $current_time]);
    $user_data = $stmt->fetch();

    if ($user_data) {
        $sql_update = ($role === 'user') 
            ? "UPDATE users SET otp_code = NULL, otp_expires_at = NULL, is_verified = 1 WHERE email = ?" 
            : "UPDATE organizers SET otp_code = NULL, otp_expires_at = NULL WHERE email = ?";
            
        $clear = $pdo->prepare($sql_update);
        $clear->execute([$email]);

        if ($role === 'organizer') {
            $_SESSION['organizer_id']    = $user_data['id'] ?? $user_data['organizer_id'] ?? null;
            $_SESSION['organizer_email'] = $user_data['email'];
            $_SESSION['role']            = 'organizer';
            header("Location: organizer_dashboard.php");
        } else {
            $_SESSION['user_id']    = $user_data['id'] ?? $user_data['user_id'] ?? null;
            $_SESSION['user_email'] = $user_data['email'];
            $_SESSION['role']       = 'user';
            header("Location: index.php");
        }
        
        unset($_SESSION['auth_email'], $_SESSION['auth_role'], $_SESSION['demo_otp']);
        exit;
    } else {
        $error = "ລະຫັດ OTP ບໍ່ຖືກຕ້ອງ ຫຼື ໝົດອາຍຸ (ເກີນ 60 ວິນາທີ)!";
    }
}
?>

<!DOCTYPE html>
<html lang="lo">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ຢືນຢັນ OTP - LAOeventMarket</title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Lao:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Noto Sans Lao', sans-serif; }
        
        /* ເພີ່ມ Background ຮູບພາບ + ແຜ່ນຟິມສີດຳຈາງໆ (Overlay) ເພື່ອໃຫ້ Card ເບິ່ງເຫັນແຈ້ງ */
        body { 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            background: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.65)), 
                        url('https://media02.stockfood.com/largepreviews/MjIxMDQzODg0OQ==/71304479-Night-market-in-Vientiane-on-the-banks-of-the-Mekong-Laos.jpg') no-repeat center center/cover;
            color: #333; 
        }

        .card { 
            width: 100%; 
            max-width: 400px; 
            background: rgba(255, 255, 255, 0.95); /* ເພີ່ມຄວາມໂປ່ງໃສເລັກນ້ອຍໃຫ້ເຂົ້າກັບ Background */
            backdrop-filter: blur(5px); 
            padding: 30px; 
            border-radius: 16px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.3); 
        }
        
        .card h3 { text-align: center; margin-bottom: 8px; font-size: 20px; color: #0f172a; }
        .card p { text-align: center; font-size: 13px; color: #64748b; margin-bottom: 20px; }
        .demo-box { background: #FEF3C7; color: #92400E; padding: 10px; border-radius: 8px; font-size: 13px; text-align: center; margin-bottom: 16px; border: 1px solid #FCD34D; }
        .error-box { background: #FEE2E2; color: #991B1B; padding: 10px; border-radius: 8px; font-size: 13px; text-align: center; margin-bottom: 16px; }
        input { width: 100%; height: 46px; text-align: center; font-size: 22px; font-weight: 700; letter-spacing: 6px; border: 1px solid #CBD5E1; border-radius: 8px; outline: none; margin-bottom: 16px; }
        input:focus { border-color: #10B981; }
        button { width: 100%; height: 44px; background: #10B981; color: #fff; border: none; border-radius: 8px; font-size: 15px; font-weight: 600; cursor: pointer; transition: 0.2s; }
        button:hover { background: #059669; }
        
        /* ປຸ່ມ Resend OTP */
        .resend-box { margin-top: 20px; text-align: center; font-size: 13px; color: #64748b; }
        .btn-resend { background: none; border: none; color: #10B981; font-weight: 600; cursor: pointer; text-decoration: underline; padding: 0; font-size: 13px; }
        .btn-resend:disabled { color: #94A3B8; text-decoration: none; cursor: not-allowed; }
    </style>
</head>
<body>

<div class="card">
    <h3>ຢືນຢັນລະຫັດ OTP</h3>
    <p>
        ສົ່ງໄປທີ່: <b><?= htmlspecialchars($email) ?></b><br>
        (ປະເພດ: <b><?= $role === 'organizer' ? 'ຜູ້ຈັດງານ (organizers)' : 'ຜູ້ໃຊ້ທົ່ວໄປ (users)' ?></b>)
    </p>

    <?php if ($error): ?>
        <div class="error-box"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="otp" maxlength="6" placeholder="123456..." required autofocus autocomplete="off">
        <button type="submit">ຢືນຢັນການເຂົ້າສູ່ລະບົບ</button>
    </form>

    <!-- ຟອມ ແລະ ເວົ້ານັບຖອຍຫຼັງ 60 ວິນາທີ -->
    <div class="resend-box">
        <form id="resendForm" action="send_otp.php" method="POST" style="display:none;">
            <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
            <input type="hidden" name="role" value="<?= htmlspecialchars($role) ?>">
        </form>

        <span id="timerText">ສາມາດຂໍລະຫັດໃໝ່ໄດ້ໃນ <b id="countdown">60</b> ວິນາທີ</span>
        <br>
        <button id="resendBtn" class="btn-resend" onclick="document.getElementById('resendForm').submit();" disabled>
            🔄 ຂໍລະຫັດ OTP ໃໝ່
        </button>
    </div>
</div>

<script>
    let timeLeft = 60;
    const countdownEl = document.getElementById('countdown');
    const resendBtn = document.getElementById('resendBtn');
    const timerText = document.getElementById('timerText');

    const timer = setInterval(() => {
        timeLeft--;
        countdownEl.textContent = timeLeft;

        if (timeLeft <= 0) {
            clearInterval(timer);
            timerText.style.display = 'none';
            resendBtn.disabled = false;
        }
    }, 1000);
</script>

</body>
</html>